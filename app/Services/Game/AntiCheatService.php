<?php

namespace App\Services\Game;

use App\Enums\Game\BanType;
use App\Models\Game\BannedUser;
use App\Models\Game\Level\Gauntlet;
use App\Models\Game\Level\Pack;
use App\Models\Game\Level\Rating;
use App\Models\Game\UserScore;
use Illuminate\Support\Facades\Log;

class AntiCheatService
{
    public function run(): array
    {
        return [
            $this->checkPlayerStars(),
            $this->checkPlayerDemons()
        ];
    }

    public function checkPlayerStars(int $baseStars = 200): bool
    {
        $ratedStars = Rating::sum('stars');
        $mapPackStars = Pack::sum('stars');
        $gauntletStars = Gauntlet::all()
            ->map(
                fn(Gauntlet $gauntlet) => array_sum([
                    $gauntlet->getRelationValue('level1')?->rating?->stars ?? 0,
                    $gauntlet->getRelationValue('level2')?->rating?->stars ?? 0,
                    $gauntlet->getRelationValue('level3')?->rating?->stars ?? 0,
                    $gauntlet->getRelationValue('level4')?->rating?->stars ?? 0,
                    $gauntlet->getRelationValue('level5')?->rating?->stars ?? 0
                ])
            )->sum();

        $totalStars = array_sum([$baseStars, $ratedStars, $mapPackStars, $gauntletStars]);
        $cheatPlayers = UserScore::where('stars', '>', $totalStars)
            ->get()
            ->map(
                fn(UserScore $score) => $score->getRelationValue('user')
            );

        foreach ($cheatPlayers as $cheatPlayer) {
            BannedUser::create([
                'type' => BanType::BAN,
                'user' => $cheatPlayer->id,
                'reason' => 'AntiCheat: AntiBan:Stars'
            ]);

            Log::channel('gdcn')
                ->info("[AntiCheat System AutoBan:Stars] Action: Banned", [
                    'user' => $cheatPlayer->id,
                    'stars' => $cheatPlayer->score->stars
                ]);
        }

        return true;
    }

    public function checkPlayerDemons(int $baseDemons = 3): bool
    {
        $levelDemons = Rating::whereDemon(true)->count();
        $totalDemons = array_sum([$baseDemons, $levelDemons]);

        $cheatPlayers = UserScore::where('demons', '>', $totalDemons)
            ->get()
            ->map(
                fn(UserScore $score) => $score->getRelationValue('user')
            );

        foreach ($cheatPlayers as $cheatPlayer) {
            BannedUser::create([
                'type' => BanType::BAN,
                'user' => $cheatPlayer->id,
                'reason' => 'AntiCheat: AntiBan:Demons'
            ]);

            Log::channel('gdcn')
                ->info("[AntiCheat System AutoBan:Stars] Action: Banned", [
                    'user' => $cheatPlayer->id,
                    'demons' => $cheatPlayer->score->demons
                ]);
        }

        return true;
    }
}
