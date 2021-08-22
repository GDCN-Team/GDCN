<?php

namespace App\Services\Game\Level;

use App\Enums\Game\Level\ScoreType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Score;
use GDCN\GDObject;
use Illuminate\Support\Facades\Log;

class ScoreService
{
    /**
     * @throws InvalidArgumentException
     */
    public function upload(int $accountID, int $levelID, int $attempts, int $percent, int $coins): Score
    {
        if ($percent <= 0 || $percent > 100) {
            Log::channel('gdcn')
                ->notice('[Level Score System] Action: Upload Score Failed', [
                    'accountID' => $accountID,
                    'levelID' => $levelID,
                    'attempts' => $attempts,
                    'percent' => $percent,
                    'coins' => $coins,
                    'reason' => 'Illegal Percent'
                ]);

            throw new InvalidArgumentException();
        }

        Log::channel('gdcn')
            ->info('[Level Score System] Action: Upload Score', [
                'accountID' => $accountID,
                'levelID' => $levelID,
                'attempts' => $attempts,
                'percent' => $percent,
                'coins' => $coins
            ]);

        return Level::findOrFail($levelID)
            ->scores()
            ->create([
                'account' => $accountID,
                'attempts' => $attempts,
                'percent' => $percent,
                'coins' => $coins
            ]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function get(int $accountID, int $levelID, ScoreType $type, int $attempts, int $percent, int $coins): string
    {
        try {
            $this->upload($accountID, $levelID, $attempts, $percent, $coins);
        } catch (InvalidArgumentException) {

        }

        $query = Score::query();
        $query->orderBy('created_at');

        switch ($type->value) {
            case ScoreType::FRIENDS:
                $query->orderByDesc('percent');
                $friends = Account::findOrFail($accountID)->friends;

                $query->whereIn(
                    'account',
                    $friends->pluck('account')
                        ->push(
                            $friends->pluck('target_account')
                        )
                );
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
        Log::channel('gdcn')
            ->info('[Level Score System] Action: Get Scores', [
                'accountID' => $accountID,
                'levelID' => $levelID,
                'type' => $type->value,
                'attempts' => $attempts,
                'percent' => $percent,
                'coins' => $coins
            ]);


        return $query->with('account.user.score')
            ->take(100)
            ->get()
            ->map(function (Score $score) {
                /** @var Account $account */
                $account = $score->getRelationValue('account');

                if ($score->percent >= 100) {
                    $rank = 1;
                } elseif ($score->percent > 75) {
                    $rank = 2;
                } else {
                    $rank = 3;
                }

                return GDObject::merge([
                    1 => $account->name,
                    2 => $account->user->id,
                    3 => $score->percent,
                    6 => $rank,
                    9 => $account->user->score->icon,
                    10 => $account->user->score->color1,
                    11 => $account->user->score->color2,
                    13 => $score->coins,
                    14 => $account->user->score->icon_type,
                    15 => $account->user->score->special,
                    16 => $account->id,
                    42 => $score->created_at->locale('en')->diffForHumans(syntax: true)
                ], ':');
            })->join('|');
    }
}
