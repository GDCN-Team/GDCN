<?php

namespace App\Services\Game;

use App\Models\Game\User;
use App\Models\Game\UserScore;
use GDCN\GDObject;

class UserScoreService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    public function update(
        string  $name,
        ?string $uuid,
        ?string $udid,
        int     $gameVersion,
        int     $binaryVersion,
        int     $stars,
        int     $demons,
        int     $diamonds,
        int     $icon,
        int     $color1,
        int     $color2,
        int     $iconType,
        int     $coins,
        int     $userCoins,
        int     $special,
        int     $accIcon,
        int     $accShip,
        int     $accBall,
        int     $accBird,
        int     $accDart,
        int     $accRobot,
        int     $accGlow,
        int     $accSpider,
        int     $accExplosion
    ): UserScore
    {
        /** @var UserScore $score */
        $score = $this->helper->resolveUser($uuid, $name, $udid)
            ->score()
            ->updateOrCreate([], [
                'game_version' => $gameVersion,
                'binary_version' => $binaryVersion,
                'stars' => $stars,
                'demons' => $demons,
                'diamonds' => $diamonds,
                'icon' => $icon,
                'color1' => $color1,
                'color2' => $color2,
                'icon_type' => $iconType,
                'coins' => $coins,
                'user_coins' => $userCoins,
                'special' => $special,
                'acc_icon' => $accIcon,
                'acc_ship' => $accShip,
                'acc_ball' => $accBall,
                'acc_bird' => $accBird,
                'acc_dart' => $accDart,
                'acc_robot' => $accRobot,
                'acc_glow' => $accGlow,
                'acc_spider' => $accSpider,
                'acc_explosion' => $accExplosion
            ]);

        $score->save();
        return $score;
    }

    public function get(?string $uuid, string $type, int $count): string
    {
        $user = $this->helper->resolveUser($uuid);

        $top = 0;
        switch ($type) {
            case 'relative':
                $stars = $user->score->stars;
                $limit = floor($count / 2);

                $query = UserScore::where('stars', '<=', $stars)->take($limit);
                $top = $query->count() - $limit;

                $query->union(UserScore::where('stars', '>=', $stars)->take($limit));
                break;
            case 'friends':
                $friends = $user->account?->friends->load(['account.user', 'target_account.user']);
                $query = UserScore::whereIn('user',
                    $friends->map(function ($friend) {
                        return $friend->getRelationValue('account')->user->id;
                    })->push(
                        $friends->map(function ($friend) {
                            return $friend->getRelationValue('target_account')->user->id;
                        })
                    )
                );
                break;
            case 'creators':
                $query = UserScore::orderByDesc('creator_points');
                break;
            case 'top':
            default:
                $query = UserScore::orderByDesc('stars');
                break;
        }

        return $query->with('user')
            ->take($count)
            ->get()
            ->map(function (UserScore $score) use (&$top) {
                /** @var User $user */
                $user = $score->getRelationValue('user');

                return GDObject::merge([
                    1 => $user->name,
                    2 => $user->id,
                    3 => $score->stars,
                    4 => $score->demons,
                    6 => ++$top,
                    7 => $user->uuid,
                    8 => $score->creator_points,
                    9 => $score->icon,
                    10 => $score->color1,
                    11 => $score->color2,
                    13 => $score->coins,
                    14 => $score->icon_type,
                    15 => $score->special,
                    16 => $user->uuid,
                    17 => $score->user_coins,
                    46 => $score->demons
                ], ':');
            })->join('|');
    }
}
