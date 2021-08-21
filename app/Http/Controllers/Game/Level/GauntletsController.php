<?php

namespace App\Http\Controllers\Game\Level;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\GauntletGetRequest;
use App\Services\Game\Level\GauntletService;

class GauntletsController extends Controller
{
    public function __construct(
        public GauntletService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJGauntlets21
     */
    public function get(GauntletGetRequest $request): string
    {
        $request->validated();
        return $this->service->get();
    }
}
