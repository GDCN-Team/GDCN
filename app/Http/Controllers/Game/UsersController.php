<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Enums\Game\UserListType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\User\InfoGetRequest;
use App\Http\Requests\Game\User\ListGetRequest;
use App\Http\Requests\Game\User\RequestAccessRequest;
use App\Http\Requests\Game\User\SearchRequest;
use App\Services\Game\UserService;

class UsersController extends Controller
{
    public function __construct(
        public UserService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJUserInfo20
     */
    public function info(InfoGetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->getInfo($data['targetAccountID'], $data['accountID'] ?? null);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJUsers20
     */
    public function search(SearchRequest $request): string
    {
        $data = $request->validated();
        return $this->service->search($data['str'], $data['page']);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/requestUserAccess
     */
    public function requestAccess(RequestAccessRequest $request): int
    {
        $data = $request->validated();
        if (!$access = $this->service->requestAccess($data['accountID'])) {
            return ResponseCode::ACCESS_FAILED;
        }

        return $access;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJUserList20
     * @throws InvalidArgumentException
     */
    public function list(ListGetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->list(
            $data['accountID'],
            UserListType::fromValue($data['type'])
        );
    }
}
