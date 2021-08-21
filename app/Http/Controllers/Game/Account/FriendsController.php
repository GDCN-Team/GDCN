<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Friend\RemoveRequest;
use App\Services\Game\Account\FriendService;

class FriendsController extends Controller
{
    public function __construct(
        protected FriendService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/removeGJFriend20
     */
    public function remove(RemoveRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->remove($data['accountID'], $data['targetAccountID'])) {
            return ResponseCode::FRIEND_REMOVE_FAILED;
        }

        return ResponseCode::FRIEND_REMOVE_SUCCESS;
    }
}
