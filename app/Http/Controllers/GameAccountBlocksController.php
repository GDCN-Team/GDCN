<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Http\Requests\GameAccountBlockRequest;
use App\Http\Requests\GameAccountUnblockRequest;
use App\Models\GameAccountBlock;
use App\Models\GameAccountFriend;
use App\Models\GameAccountFriendRequest;
use App\Models\GameAccountMessage;
use Exception;

/**
 * Class GameAccountBlocksController
 * @package App\Http\Controllers
 */
class GameAccountBlocksController extends Controller
{
    /**
     * @param GameAccountBlockRequest $request
     * @param GameAccountMessage $message
     * @param GameAccountFriend $friend
     * @param GameAccountFriendRequest $friendRequest
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/blockGJUser20
     */
    public function block(GameAccountBlockRequest $request, GameAccountMessage $message, GameAccountFriend $friend, GameAccountFriendRequest $friendRequest): int
    {
        $data = $request->validated();

        $message->findEach($data['accountID'], $data['targetAccountID'])->delete();
        $friend->findEach($data['accountID'], $data['targetAccountID'])->delete();
        $friendRequest->findEach($data['accountID'], $data['targetAccountID'])->delete();

        GameAccountBlock::firstOrNew([
            'account' => $data['accountID'],
            'target_account' => $data['targetAccountID']
        ])->save();

        return ResponseCode::OK;
    }

    /**
     * @param GameAccountUnblockRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/unblockGJUser20
     */
    public function unblock(GameAccountUnblockRequest $request): int
    {
        try {
            $request->block->delete();
        } catch (Exception $e) {
            return ResponseCode::DELETE_FAILED;
        }

        return ResponseCode::OK;
    }
}
