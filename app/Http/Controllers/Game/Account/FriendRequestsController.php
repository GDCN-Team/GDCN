<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Friend\Request\AcceptFriendRequest;
use App\Http\Requests\Game\Account\Friend\Request\DeleteRequest;
use App\Http\Requests\Game\Account\Friend\Request\GetRequest;
use App\Http\Requests\Game\Account\Friend\Request\ReadRequest;
use App\Http\Requests\Game\Account\Friend\Request\UploadRequest;
use App\Services\Game\Account\FriendRequestService;

/**
 * Class FriendRequestsController
 * @package App\Http\Controllers\Game\Account
 */
class FriendRequestsController extends Controller
{
    /**
     * @var FriendRequestService
     */
    protected $service;

    /**
     * FriendRequestsController constructor.
     * @param FriendRequestService $service
     */
    public function __construct(FriendRequestService $service)
    {
        $this->service = $service;
    }

    /**
     * @param UploadRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadFriendRequest20
     */
    public function upload(UploadRequest $request): int
    {
        $data = $request->validated();
        $request = $this->service->upload($data['accountID'], $data['toAccountID'], $data['comment']);
        return !empty($request) ? $request->id : ResponseCode::FRIEND_REQUEST_UPLOAD_FAILED;
    }

    /**
     * @param DeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJFriendRequests20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        $result = $request->has('accounts') ? $this->service->multiDelete(
            $data['accountID'],
            explode(',', $data['accounts']),
            $data['isSender']
        ) : $this->service->singleDelete(
            $data['accountID'],
            $data['targetAccountID'],
            $data['isSender']
        );

        return $result ? ResponseCode::FRIEND_REQUEST_DELETE_SUCCESS : ResponseCode::FRIEND_REQUEST_DELETE_FAILED;
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJFriendRequests20
     */
    public function get(GetRequest $request)
    {
        try {
            $data = $request->validated();
            return $this->service->list($data['accountID'], $data['getSent'], $data['page']);
        } catch (NoItemException $e) {
            return ResponseCode::EMPTY_RESULT;
        }
    }

    /**
     * @param ReadRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/readGJFriendRequest20
     */
    public function read(ReadRequest $request): int
    {
        $data = $request->validated();
        return $this->service->read($data['accountID'], $data['requestID'])
            ? ResponseCode::FRIEND_REQUEST_READ_SUCCESS : ResponseCode::FRIEND_REQUEST_READ_FAILED;
    }

    /**
     * @param AcceptFriendRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/acceptGJFriendRequest20
     */
    public function accept(AcceptFriendRequest $request): int
    {
        $data = $request->validated();
        return $this->service->accept($data['accountID'], $data['targetAccountID'], $data['requestID'])
            ? ResponseCode::FRIEND_REQUEST_ACCEPT_SUCCESS : ResponseCode::FRIEND_REQUEST_ACCEPT_FAILED;
    }
}
