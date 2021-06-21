<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameLevelGauntletGetRequest;
use App\Models\GameLevelGauntlet;
use GDCN\GDObject;

/**
 * Class LevelGauntletsController
 * @package App\Http\Controllers
 */
class LevelGauntletsController extends Controller
{
    /**
     * @param GameLevelGauntletGetRequest $request
     * @param HashesController $hash
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJGauntlets21
     */
    public function get(GameLevelGauntletGetRequest $request, HashesController $hash): string
    {
        $request->validated();

        $gauntlets = GameLevelGauntlet::all()
            ->map(function (GameLevelGauntlet $gauntlet) {
                return GDObject::merge([
                    1 => $gauntlet->id,
                    3 => $gauntlet->levels
                ], ':');
            })->toArray();

        $result = implode('|', $gauntlets);
        return "{$result}#{$hash->generateLevelGauntletHash(GameLevelGauntlet::all())}";
    }
}
