<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Friend\Request\RemoveRequest;
use App\Models\GameAccountFriend;

/**
 * Class AccountFriendsController
 * @package App\Http\Controllers
 */
class AccountFriendsController extends Controller
{
    /**
     * @param RemoveRequest $request
     * @param GameAccountFriend $friend
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/removeGJFriend20
     */
    public function remove(RemoveRequest $request, GameAccountFriend $friend): int
    {
        try {
            $data = $request->validated();
            $request->auth();

            $friend->findEach($data['accountID'], $data['targetAccountID'])->delete();
            return ResponseCode::OK;
        } catch (AuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        }
    }
}
