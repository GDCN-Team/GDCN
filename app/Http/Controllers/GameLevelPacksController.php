<?php

namespace App\Http\Controllers;

use App\Game\Helpers;
use App\Http\Requests\GameLevelPackGetRequest;
use App\Models\GameLevelPack;
use GDCN\GDObject;

/**
 * Class GameLevelPacksController
 * @package App\Http\Controllers
 */
class GameLevelPacksController extends Controller
{
    /**
     * @param GameLevelPackGetRequest $request
     * @param Helpers $helper
     * @param GameHashesController $hash
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJMapPacks21
     */
    public function get(GameLevelPackGetRequest $request, Helpers $helper, GameHashesController $hash): string
    {
        $data = $request->validated();

        $query = GameLevelPack::all();
        $page = $data['page'];

        $packs = $query->forPage(++$page, $helper->perPage)
            ->map(function (GameLevelPack $pack) {
                return GDObject::merge([
                    1 => $pack->id,
                    2 => $pack->name,
                    3 => $pack->levels,
                    4 => $pack->stars,
                    5 => $pack->coins,
                    6 => $pack->difficulty,
                    7 => $pack->text_color,
                    8 => $pack->bar_color
                ], ':');
            })->join('|');

        return "{$packs}#{$helper->generatePageHash($query->count(), $page)}#{$hash->generateLevelPackHash($query)}";
    }
}
