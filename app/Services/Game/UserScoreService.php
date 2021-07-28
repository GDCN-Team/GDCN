<?php

namespace App\Services\Game;

use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Account;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use GDCN\GDObject;

/**
 * Class UserScoreService
 * @package App\Services\Game\
 */
class UserScoreService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    /**
     * @param $user
     * @param int $game_version
     * @param int $binary_version
     * @param int $stars
     * @param int $demons
     * @param int $diamonds
     * @param int $icon
     * @param int $color1
     * @param int $color2
     * @param int $icon_type
     * @param int $coins
     * @param int $user_coins
     * @param int $special
     * @param int $acc_icon
     * @param int $acc_ship
     * @param int $acc_ball
     * @param int $acc_bird
     * @param int $acc_dart
     * @param int $acc_robot
     * @param int $acc_glow
     * @param int $acc_spider
     * @param int $acc_explosion
     * @return bool
     */
    public function update(
        $user,
        int $game_version,
        int $binary_version,
        int $stars,
        int $demons,
        int $diamonds,
        int $icon,
        int $color1,
        int $color2,
        int $icon_type,
        int $coins,
        int $user_coins,
        int $special,
        int $acc_icon,
        int $acc_ship,
        int $acc_ball,
        int $acc_bird,
        int $acc_dart,
        int $acc_robot,
        int $acc_glow,
        int $acc_spider,
        int $acc_explosion
    ): bool
    {
        /** @var User $user */
        $user = $this->helper->getModel($user, User::class);

        $score = $user->score ?? new UserScore();
        $score->user = $user->id;
        $score->game_version = $game_version;
        $score->binary_version = $binary_version;
        $score->stars = $stars;
        $score->demons = $demons;
        $score->diamonds = $diamonds;
        $score->icon = $icon;
        $score->color1 = $color1;
        $score->color2 = $color2;
        $score->icon_type = $icon_type;
        $score->coins = $coins;
        $score->user_coins = $user_coins;
        $score->special = $special;
        $score->acc_icon = $acc_icon;
        $score->acc_ship = $acc_ship;
        $score->acc_ball = $acc_ball;
        $score->acc_bird = $acc_bird;
        $score->acc_dart = $acc_dart;
        $score->acc_robot = $acc_robot;
        $score->acc_glow = $acc_glow;
        $score->acc_spider = $acc_spider;
        $score->acc_explosion = $acc_explosion;
        return $score->save();
    }

    /**
     * @param string $type
     * @param int $count
     * @param $user
     * @return string
     */
    public function get(string $type, int $count, $user): string
    {
        $top = 0;
        switch ($type) {
            case 'relative':
                $stars = $user->score->stars ?? 0;
                $limit = floor($count / 2);

                $query = UserScore::where('stars', '<=', $stars)->take($limit);
                $top = $query->count() + 1;

                $query->union(UserScore::where('stars', '>=', $stars)->take($limit));
                break;
            case 'friends':
                $query = UserScore::whereIn('user', optional($user->account->friends ?? null)->pluck('user.id'));
                break;
            case 'creators':
                $query = UserScore::orderByDesc('creator_points');
                break;
            case 'top':
            default:
                $query = UserScore::orderByDesc('stars');
                break;
        }

        return $query->with('owner')
            ->take($count)
            ->get()
            ->map(function (UserScore $score) use ($top) {
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
    }
}
