<?php

namespace App\Http\Controllers\Game\Level;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\PackGetRequest;
use App\Services\Game\Level\PackService;

class PacksController extends Controller
{
    public function __construct(
        public PackService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJMapPacks21
     */
    public function get(PackGetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->get($data['page']);
    }
}
