<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Friend\Request\RemoveRequest;
use App\Services\Game\Account\FriendService;

/**
 * Class FriendsController
 * @package App\Http\Controllers
 */
class FriendsController extends Controller
{
    /**
     * @var FriendService
     */
    protected $service;

    /**
     * FriendsController constructor.
     * @param FriendService $service
     */
    public function __construct(FriendService $service)
    {
        $this->service = $service;
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
        return $this->service->remove($data['accountID'], $data['targetAccountID'])
            ? ResponseCode::FRIEND_REMOVE_SUCCESS : ResponseCode::FRIEND_REMOVE_FAILED;
    }
}
