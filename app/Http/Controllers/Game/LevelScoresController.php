<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\LevelScoreType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\ScoreGetRequest;
use App\Models\GameAccount;
use App\Models\GameLevelScore;
use GDCN\GDObject;
use Illuminate\Support\Carbon;

/**
 * Class LevelScoresController
 * @package App\Http\Controllers
 */
class LevelScoresController extends Controller
{
    /**
     * @param ScoreGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJLevelScores211
     */
    public function get(ScoreGetRequest $request)
    {
        $data = $request->validated();


        /*
                try {
                    $hash = app(HashesController::class);
                    $hash->checkChk(
                        $hash->generateUploadLevelScoreChk($data['accountID'], $data['levelID'], $data['percent'], $data['s3'], $data['s2'], $data['s8'], $data['s4'], $data['s6'], $data['s9'], $data['s10'], $data['s7']),
                        $hash->decodeChk($data['chk'], $hash->keys['level_score'])
                    );
                } catch (ChkValidateException $e) {
                    dd($e);
                    return ResponseCode::CHK_CHECK_FAILED;
                }
        */

        if ($data['percent'] > 0) {
            GameLevelScore::query()
                ->updateOrCreate([
                    'account' => $data['accountID'],
                    'level' => $data['levelID']
                ], [
                    'attempts' => $data['s8'],
                    'coins' => ($data['s9'] - 5819),
                    'percent' => $data['percent']
                ]);
        }

        $query = GameLevelScore::query();
        switch ($data['type']) {
            case 0: // Friends
                Carbon::setLocale('en');

                try {
                    $request->auth();

                    /** @var GameAccount $account */
                    $account = $request->user();
                } catch (AuthenticationException $e) {
                    return ResponseCode::LOGIN_FAILED;
                }

                $query->orderByDesc('attempts')
                    ->orderByDesc('created_at')
                    ->whereIn('account', $account->friends->pluck('id'));
                break;
            case 1: // Top
                $query->orderByDesc('attempts')
                    ->orderByDesc('created_at');
                break;
            case 2: // Week
                $time = Carbon::now()
                    ->subWeek();

                $query->orderByDesc('attempts')
                    ->orderByDesc('created_at')
                    ->where('created_at', '>', $time);
                break;
            default:
                return ResponseCode::INVALID_REQUEST;
        }

        $count = $query->count();
        if ($count <= 0) {
            return ResponseCode::EMPTY_RESULT_FAILED;
        }

        Carbon::setLocale('en');
        return $query->get()
            ->map(function (GameLevelScore $score) {
                /** @var GameAccount $account */
                $account = $score->owner()
                    ->with(['user', 'user.score'])
                    ->first();

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
                    9 => $account->user->score->icon ?? 0,
                    10 => $account->user->score->color1 ?? 0,
                    11 => $account->user->score->color2 ?? 3,
                    13 => $score->coins,
                    14 => $account->user->score->icon_type ?? 0,
                    15 => $account->user->score->special ?? 0,
                    16 => $account->id,
                    42 => $score->created_at->diffForHumans(null, true)
                ], ':');
            })->join('|');
    }
}
