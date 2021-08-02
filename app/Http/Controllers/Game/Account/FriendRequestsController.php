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

class FriendRequestsController extends Controller
{
    /**
     * @param FriendRequestService $service
     */
    public function __construct(
        protected FriendRequestService $service
    )
    {
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
        if ($request = $this->service->upload($data['accountID'], $data['toAccountID'], $data['comment'])) {
            return $request->id;
        } else {
            return ResponseCode::FRIEND_REQUEST_UPLOAD_FAILED;
        }
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
        if ($request->has('accounts')) {
            $result = $this->service->multiDelete($data['accountID'], explode(',', $data['accounts']), $data['isSender']);
        } else {
            $result = $this->service->singleDelete($data['accountID'], $data['targetAccountID'], $data['isSender']);
        }

        if ($result) {
            return ResponseCode::FRIEND_REQUEST_DELETE_SUCCESS;
        } else {
            return ResponseCode::FRIEND_REQUEST_DELETE_FAILED;
        }
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJFriendRequests20
     */
    public function get(GetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->list($data['accountID'], $data['getSent'] ?? false, $data['page']);
        } catch (NoItemException) {
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
        if ($this->service->read($data['accountID'], $data['requestID'])) {
            return ResponseCode::FRIEND_REQUEST_READ_SUCCESS;
        } else {
            return ResponseCode::FRIEND_REQUEST_READ_FAILED;
        }
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
        if ($this->service->accept($data['accountID'], $data['targetAccountID'], $data['requestID'])) {
            return ResponseCode::FRIEND_REQUEST_ACCEPT_SUCCESS;
        } else {
            return ResponseCode::FRIEND_REQUEST_ACCEPT_FAILED;
        }
    }
}
