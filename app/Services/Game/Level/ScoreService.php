<?php

namespace App\Services\Game\Level;

use App\Enums\Game\Level\ScoreType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\Level\Score;
use App\Services\Game\HelperService;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ScoreService
 * @package App\Services\Game\Level
 */
class ScoreService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    /**
     * @param $account
     * @param $level
     * @param int $attempts
     * @param int $percent
     * @param int $coins
     * @return Score|Model
     * @throws InvalidArgumentException
     */
    public function upload($account, $level, int $attempts, int $percent, int $coins): Model|Score
    {
        if ($percent <= 0) {
            throw new InvalidArgumentException();
        }

        return Score::updateOrCreate([
            'account' => $this->helper->getID($account),
            'level' => $this->helper->getID($level)
        ], [
            'attempts' => $attempts,
            'percent' => $percent,
            'coins' => $coins
        ]);
    }

    /**
     * @param $account
     * @param $level
     * @param ScoreType $type
     * @param int $attempts
     * @param int $percent
     * @param int $coins
     * @return int|string
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function get($account, $level, ScoreType $type, int $attempts, int $percent, int $coins): int|string
    {
        try {
            $this->upload($account, $level, $attempts, $percent, $coins);
        } catch (InvalidArgumentException) {

        }

        $query = Score::query();
        $query->orderBy('created_at');

        switch ($type->value) {
            case ScoreType::FRIENDS:
                $query->orderByDesc('percent');
                $account = $this->helper->getModel($account, Account::class);
                $query->whereIn('account', $account->friends->pluck('id'));
                break;
            case ScoreType::TOP:
                $query->orderByDesc('percent');
                break;
            case ScoreType::WEEK:
                $query->orderByDesc('percent');
                $query->where('created_at', '>', now()->subWeek());
                break;
            default:
                throw new InvalidArgumentException();
        }

        $count = $query->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        $this->helper->setCarbonLocaleToEnglish();
        return $query->with('owner.user.score')
            ->take(100)
            ->get()
            ->map(function (Score $score) {
                if ($score->percent >= 100) {
                    $rank = 1;
                } elseif ($score->percent > 75) {
                    $rank = 2;
                } else {
                    $rank = 3;
                }

                return GDObject::merge([
                    1 => $score->owner->name,
                    2 => $score->owner->user->id,
                    3 => $score->percent,
                    6 => $rank,
                    9 => $score->owner->user->score->icon ?? 0,
                    10 => $score->owner->user->score->color1 ?? 0,
                    11 => $score->owner->user->score->color2 ?? 3,
                    13 => $score->coins,
                    14 => $score->owner->user->score->icon_type ?? 0,
                    15 => $score->owner->user->score->special ?? 0,
                    16 => $score->owner->id,
                    42 => $score->created_at->diffForHumans(null, true)
                ], ':');
            })->join('|');
    }
}
