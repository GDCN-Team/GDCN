<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Block\BlockRequest;
use App\Http\Requests\Game\Account\Block\UnblockRequest;
use App\Services\Game\Account\BlockService;

class BlocksController extends Controller
{
    /**
     * @param BlockService $service
     */
    public function __construct(
        protected BlockService $service
    )
    {
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
        if ($this->service->block($data['accountID'], $data['targetAccountID'])) {
            return ResponseCode::BLOCK_SUCCESS;
        } else {
            return ResponseCode::BLOCK_FAILED;
        }
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
        if ($this->service->unblock($data['accountID'], $data['targetAccountID'])) {
            return ResponseCode::UNBLOCK_SUCCESS;
        } else {
            return ResponseCode::UNBLOCK_FAILED;
        }
    }
}
