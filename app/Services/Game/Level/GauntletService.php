<?php

namespace App\Services\Game\Level;

use App\Models\Game\Level\Gauntlet;
use GDCN\GDObject;
use GDCN\Hash\Components\LevelGauntlet as LevelGauntletComponent;

class GauntletService
{
    public function get(): string
    {
        $hash = null;
        $result = Gauntlet::all()
            ->map(function (Gauntlet $gauntlet) use (&$hash) {
                $hash .= implode(null, [
                    $gauntlet->gauntlet_id,
                    $gauntlet->level1,
                    $gauntlet->level2,
                    $gauntlet->level3,
                    $gauntlet->level4,
                    $gauntlet->level5
                ]);

                return GDObject::merge([
                    1 => $gauntlet->gauntlet_id,
                    3 => implode(',', [
                        $gauntlet->level1,
                        $gauntlet->level2,
                        $gauntlet->level3,
                        $gauntlet->level4,
                        $gauntlet->level5
                    ])
                ], ':');
            })->join('|');

        return implode('#', [
            $result,
            app(LevelGauntletComponent::class)->generateHash($hash)
        ]);
    }
}
