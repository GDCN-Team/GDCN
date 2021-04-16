<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameLevelGauntletGetRequest;
use App\Models\GameLevelGauntlet;
use GDCN\GDObject;

/**
 * Class GameLevelGauntletsController
 * @package App\Http\Controllers
 */
class GameLevelGauntletsController extends Controller
{
    /**
     * @param GameLevelGauntletGetRequest $request
     * @param GameHashesController $hash
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJGauntlets21
     */
    public function get(GameLevelGauntletGetRequest $request, GameHashesController $hash): string
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
