<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\GameAuthenticationException;
use App\Exceptions\GameChkValidateException;
use App\Exceptions\GameUserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameUserScoreGetRequest;
use App\Http\Requests\GameUserScoreUpdateRequest;
use App\Models\GameAccount;
use App\Models\GameUserScore;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Validation\ValidationException;

/**
 * Class UserScoresController
 * @package App\Http\Controllers
 */
class UserScoresController extends Controller
{
    /**
     * @param GameUserScoreUpdateRequest $request
     * @param HashesController $hash
     * @return HigherOrderBuilderProxy|int|mixed
     *
     * @see http://docs.gdprogra.me/#/endpoints/updateGJUserScore22
     */
    public function update(GameUserScoreUpdateRequest $request, HashesController $hash)
    {
        $data = $request->validated();

        try {
            $hash->checkChk(
                $hash->generateUploadUserScoreChk($data['accountID'] ?? 0, $data['userCoins'], $data['demons'], $data['stars'], $data['coins'], $data['iconType'], $data['icon'], $data['diamonds'], $data['accIcon'], $data['accShip'], $data['accBall'], $data['accBird'], $data['accDart'], $data['accRobot'], $data['accGlow'], $data['accSpider'], $data['accExplosion']),
                $hash->decodeChk($data['seed2'], $hash->keys['user'])
            );
        } catch (GameChkValidateException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        }

        try {
            $user = $request->getGameUser();
        } catch (GameUserNotFoundException $e) {
            return ResponseCode::USER_NOT_FOUND;
        }

        $user->score()
            ->updateOrCreate([
                'user' => $user->id
            ], [
                'game_version' => $data['gameVersion'],
                'binary_version' => $data['binaryVersion'],
                'stars' => $data['stars'],
                'demons' => $data['demons'],
                'diamonds' => $data['diamonds'],
                'icon' => $data['icon'],
                'color1' => $data['color1'],
                'color2' => $data['color2'],
                'icon_type' => $data['iconType'],
                'coins' => $data['coins'],
                'user_coins' => $data['userCoins'],
                'special' => $data['special'],
                'acc_icon' => $data['accIcon'],
                'acc_ship' => $data['accShip'],
                'acc_ball' => $data['accBall'],
                'acc_bird' => $data['accBird'],
                'acc_dart' => $data['accDart'],
                'acc_robot' => $data['accRobot'],
                'acc_glow' => $data['accGlow'],
                'acc_spider' => $data['accSpider'],
                'acc_explosion' => $data['accExplosion']
            ]);

        return $user->id;
    }

    /**
     * @param GameUserScoreGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJScores20
     */
    public function get(GameUserScoreGetRequest $request)
    {
        try {
            $top = 0;
            $data = $request->validated();
            $query = GameUserScore::query();

            switch ($data['type']) {
                case 'top':
                    $query->orderByDesc('stars');
                    break;
                case 'friends':

                    try {
                        $request->auth();
                        $account = $request->user();
                    } catch (GameAuthenticationException $e) {
                        return ResponseCode::LOGIN_FAILED;
                    }

                    $friends = $account->friends
                        ->map(function (GameAccount $friend) {
                            return $friend->user->id;
                        })->toArray();

                    $query->whereIn('user', $friends);
                    $query->orderByDesc('stars');
                    break;
                case 'relative':
                    $stars = $user->score->stars ?? 0;
                    $query->where('stars', '<=', $stars)
                        ->orWhere('stars', '>=', $stars)
                        ->orderByDesc('stars')
                        ->distinct();

                    $top = $query->count() - 1;
                    break;
                case 'creators':
                    $query->orderByDesc('creator_points');
                    break;
                default:
                    return ResponseCode::INVALID_REQUEST;
            }

            $count = $query->count();
            if ($count <= 0) {
                return ResponseCode::EMPTY_RESULT_FAILED;
            }

            return $query->with('owner')
                ->take($data['count'])
                ->get()
                ->map(function (GameUserScore $score) use ($top) {
                    return GDObject::merge([
                        1 => $score->owner->name,
                        2 => $score->owner->id,
                        3 => $score->stars,
                        4 => $score->demons,
                        6 => ++$top,
                        7 => $score->owner->uuid,
                        8 => $score->creator_points,
                        9 => $score->icon,
                        10 => $score->color1,
                        11 => $score->color2,
                        13 => $score->coins,
                        14 => $score->icon_type,
                        15 => $score->special,
                        16 => $score->owner->uuid,
                        17 => $score->user_coins,
                        46 => $score->demons
                    ], ':');
                })->join('|');
        } catch (ValidationException $e) {
            return ResponseCode::REQUEST_CHECK_FAILED;
        }
    }
}
