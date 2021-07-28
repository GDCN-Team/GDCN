<?php

namespace App\Http\Controllers\Game\Level;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\GauntletGetRequest;
use App\Services\Game\Level\GauntletService;

/**
 * Class GauntletsController
 * @package App\Http\Controllers
 */
class GauntletsController extends Controller
{
    public function __construct(
        public GauntletService $service
    )
    {
    }

    /**
     * @param GauntletGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJGauntlets21
     */
    public function get(GauntletGetRequest $request): string
    {
        $request->validated();
        return $this->service->get();
    }
}
