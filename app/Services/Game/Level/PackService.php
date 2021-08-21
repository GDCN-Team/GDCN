<?php

namespace App\Services\Game\Level;

use App\Models\Game\Level\Pack;
use GDCN\GDObject;
use GDCN\Hash\Components\LevelPack as LevelPackComponent;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;

class PackService
{
    public function get(int $page): string
    {
        $hash = null;
        $result = Pack::query()
            ->forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (Pack $pack) use (&$hash) {
                $hash .= implode(null, [
                    substr($pack->id, 0, 1),
                    substr($pack->id, -1),
                    $pack->stars,
                    $pack->coins
                ]);

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

        $count = Pack::count();
        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($count, $page),
            app(LevelPackComponent::class)->generateHash($hash)
        ]);
    }
}
