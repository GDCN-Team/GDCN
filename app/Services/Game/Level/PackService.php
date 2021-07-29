<?php

namespace App\Services\Game\Level;

use App\Exceptions\Game\NoItemException;
use App\Models\Game\Level\Pack;
use App\Services\Game\HelperService;
use GDCN\GDObject;
use GDCN\Hash\Hasher;

/**
 * Class PackService
 * @package App\Services\Game\Level
 */
class PackService
{
    public function __construct(
        public HelperService $helper,
        public Hasher $hash
    )
    {
    }

    /**
     * @param int $page
     * @return string
     * @throws NoItemException
     */
    public function get(int $page): string
    {
        $packs = Pack::query();
        $count = $packs->count();

        if ($count <= 0) {
            throw new NoItemException();
        }

        $hash = null;
        $result = $packs->forPage(++$page, $this->helper->perPage)
            ->get()
            ->map(function (Pack $pack) use (&$hash) {
                $hash .= implode(null, [substr($pack->id, 0, 1), substr($pack->id, -1), $pack->stars, $pack->coins]);

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

        return $result . '#' . $this->helper->generatePageHash($count, $page) . '#' . $this->hash->generateHashForPack($hash);
    }
}
