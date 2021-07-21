<?php

namespace App\Http\Controllers\Game\Level;

use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Game\HashesController;
use App\Http\Requests\Game\Level\PackGetRequest;
use App\Models\Game\Level\Pack;
use GDCN\GDObject;

/**
 * Class PacksController
 * @package App\Http\Controllers
 */
class PacksController extends Controller
{
    /**
     * @param PackGetRequest $request
     * @param Helpers $helper
     * @param HashesController $hash
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJMapPacks21
     */
    public function get(PackGetRequest $request, Helpers $helper, HashesController $hash): string
    {
        $data = $request->validated();

        $query = Pack::all();
        $page = $data['page'];

        $packs = $query->forPage(++$page, $helper->perPage)
            ->map(function (Pack $pack) {
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
