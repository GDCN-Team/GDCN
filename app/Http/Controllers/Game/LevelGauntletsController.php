<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\GauntletGetRequest;
use App\Models\GameLevelGauntlet;
use GDCN\GDObject;

/**
 * Class LevelGauntletsController
 * @package App\Http\Controllers
 */
class LevelGauntletsController extends Controller
{
    /**
     * @param GauntletGetRequest $request
     * @param HashesController $hash
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJGauntlets21
     */
    public function get(GauntletGetRequest $request, HashesController $hash): string
    {
        $request->validated();

        $gauntlets = GameLevelGauntlet::all();
        $result = $gauntlets->map(function (GameLevelGauntlet $gauntlet) {
            return GDObject::merge([
                1 => $gauntlet->id,
                3 => $gauntlet->levelIds
            ], ':');
        })->join('|');

        return "$result#{$hash->generateLevelGauntletHash($gauntlets)}";
    }
}
