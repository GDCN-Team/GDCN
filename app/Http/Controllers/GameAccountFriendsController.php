<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Exceptions\GameAuthenticationException;
use App\Http\Requests\GameAccountFriendRemoveRequest;
use App\Models\GameAccountFriend;

/**
 * Class GameAccountFriendsController
 * @package App\Http\Controllers
 */
class GameAccountFriendsController extends Controller
{
    /**
     * @param GameAccountFriendRemoveRequest $request
     * @param GameAccountFriend $friend
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/removeGJFriend20
     */
    public function remove(GameAccountFriendRemoveRequest $request, GameAccountFriend $friend): int
    {
        try {
            $data = $request->validated();
            $request->auth();

            $friend->findEach($data['accountID'], $data['targetAccountID'])->delete();
            return ResponseCode::OK;
        } catch (GameAuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        }
    }
}
