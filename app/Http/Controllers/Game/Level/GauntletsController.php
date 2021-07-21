<?php

namespace App\Http\Controllers\Game\Level;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Game\HashesController;
use App\Http\Requests\Game\Level\GauntletGetRequest;
use App\Models\Game\Level\Gauntlet;
use GDCN\GDObject;

/**
 * Class GauntletsController
 * @package App\Http\Controllers
 */
class GauntletsController extends Controller
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

        $gauntlets = Gauntlet::all();
        $result = $gauntlets->map(function (Gauntlet $gauntlet) {
            return GDObject::merge([
                1 => $gauntlet->id,
                3 => $gauntlet->levelIds
            ], ':');
        })->join('|');

        return "$result#{$hash->generateLevelGauntletHash($gauntlets)}";
    }
}
