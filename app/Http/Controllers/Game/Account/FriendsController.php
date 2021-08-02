<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Friend\Request\RemoveRequest;
use App\Services\Game\Account\FriendService;


class FriendsController extends Controller
{
    /**
     * @param FriendService $service
     */
    public function __construct(
        protected FriendService $service
    )
    {
    }

    /**
     * @param RemoveRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/removeGJFriend20
     */
    public function remove(RemoveRequest $request): int
    {
        $data = $request->validated();
        if ($this->service->remove($data['accountID'], $data['targetAccountID'])) {
            return ResponseCode::FRIEND_REMOVE_SUCCESS;
        } else {
            return ResponseCode::FRIEND_REMOVE_FAILED;
        }
    }
}
