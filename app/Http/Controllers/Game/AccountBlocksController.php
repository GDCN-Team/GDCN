<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Block\BlockRequest;
use App\Http\Requests\Game\Account\Block\UnblockRequest;
use App\Services\Game\AccountBlockService;
use Exception;

/**
 * Class AccountBlocksController
 * @package App\Http\Controllers
 */
class AccountBlocksController extends Controller
{
    /**
     * @var AccountBlockService
     */
    protected $service;

    /**
     * AccountBlocksController constructor.
     * @param AccountBlockService $service
     */
    public function __construct(AccountBlockService $service)
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
        return $this->service->block($data['accountID'], $data['targetAccountID'])->exists()
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
        try {
            $request->block->delete();
            return ResponseCode::UNBLOCK_SUCCESS;
        } catch (Exception $e) {
            return ResponseCode::UNBLOCK_FAILED;
        }
    }
}
