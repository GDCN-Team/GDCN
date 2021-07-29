<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Enums\Game\UserListType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\User\InfoGetRequest;
use App\Http\Requests\Game\User\ListGetRequest;
use App\Http\Requests\Game\User\RequestAccessRequest;
use App\Http\Requests\Game\User\SearchRequest;
use App\Services\Game\UserService;
use Illuminate\Support\Facades\Auth;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    public function __construct(
        public UserService $service
    )
    {
    }

    /**
     * @param InfoGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJUserInfo20
     */
    public function info(InfoGetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->getInfo($data['targetAccountID'], $data['accountID'] ?? null);
    }

    /**
     * @param SearchRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJUsers20
     */
    public function search(SearchRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->search($data['str'], $data['page']);
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT;
        }
    }

    /**
     * @param RequestAccessRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/requestUserAccess
     */
    public function requestAccess(RequestAccessRequest $request): int
    {
        $request->validated();
        return $this->service->requestAccess(Auth::user()) ?? ResponseCode::ACCESS_FAILED;
    }

    /**
     * @param ListGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJUserList20
     */
    public function list(ListGetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->list(
                $data['accountID'],
                UserListType::fromValue((int)$data['type'])
            );
        } catch (InvalidArgumentException) {
            return ResponseCode::INVALID_REQUEST;
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT;
        }
    }
}
