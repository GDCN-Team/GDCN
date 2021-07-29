<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\PackGetRequest;
use App\Services\Game\Level\PackService;

/**
 * Class PacksController
 * @package App\Http\Controllers
 */
class PacksController extends Controller
{
    public function __construct(
        public PackService $service
    )
    {
    }

    /**
     * @param PackGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJMapPacks21
     */
    public function get(PackGetRequest $request): string
    {
        try {
            $data = $request->validated();
            return $this->service->get($data['page']);
        } catch (NoItemException) {
            return ResponseCode::LEVEL_PACK_EMPTY_RESULT_STRING;
        }
    }
}
