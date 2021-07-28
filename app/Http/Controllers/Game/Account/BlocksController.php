<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Block\BlockRequest;
use App\Http\Requests\Game\Account\Block\UnblockRequest;
use App\Services\Game\Account\BlockService;

/**
 * Class BlocksController
 * @package App\Http\Controllers
 */
class BlocksController extends Controller
{
    /**
     * @var BlockService
     */
    protected $service;

    /**
     * BlocksController constructor.
     * @param BlockService $service
     */
    public function __construct(BlockService $service)
    {
        $this->service = $service;
    }

    /**
     * @param BlockRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/blockGJUser20
     */
    public function block(BlockRequest $request): int
    {
        $data = $request->validated();
        return $this->service->block($data['accountID'], $data['targetAccountID'])
            ? ResponseCode::BLOCK_SUCCESS : ResponseCode::BLOCK_FAILED;
    }

    /**
     * @param UnblockRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/unblockGJUser20
     */
    public function unblock(UnblockRequest $request): int
    {
        $data = $request->validated();
        return $this->service->unblock($data['accountID'], $data['targetAccountID'])
            ? ResponseCode::UNBLOCK_SUCCESS : ResponseCode::UNBLOCK_FAILED;
    }
}
