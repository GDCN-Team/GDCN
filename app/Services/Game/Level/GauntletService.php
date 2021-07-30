<?php

namespace App\Services\Game\Level;

use App\Models\Game\Level\Gauntlet;
use GDCN\GDObject;
use GDCN\Hash\Hasher;

/**
 * Class GauntletService
 * @package App\Services\Game\Level
 */
class GauntletService
{
    /**
     * GauntletService constructor.
     * @param Hasher $hash
     */
    public function __construct(
        public Hasher $hash
    )
    {
    }

    /**
     * @return string
     */
    public function get(): string
    {
        $hash = '';
        $gauntlets = Gauntlet::all();
        $result = $gauntlets->map(function (Gauntlet $gauntlet) use (&$hash) {
            $hash .= implode(null, [$gauntlet->gauntlet_id, $gauntlet->level1, $gauntlet->level2, $gauntlet->level3, $gauntlet->level4, $gauntlet->level5]);

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

        return $result . '#' . $this->hash->generateHashForGauntlet($hash);
    }
}
