<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Friend\Request\AcceptFriendRequest;
use App\Http\Requests\Game\Account\Friend\Request\DeleteRequest;
use App\Http\Requests\Game\Account\Friend\Request\GetRequest;
use App\Http\Requests\Game\Account\Friend\Request\ReadRequest;
use App\Http\Requests\Game\Account\Friend\Request\UploadRequest;
use App\Services\Game\Account\FriendRequestService;
use Illuminate\Support\Arr;

class FriendRequestsController extends Controller
{
    public function __construct(
        protected FriendRequestService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/uploadFriendRequest20
     */
    public function upload(UploadRequest $request): int
    {
        $data = $request->validated();
        if (!$request = $this->service->upload($data['accountID'], $data['toAccountID'], $data['comment'])) {
            return ResponseCode::FRIEND_REQUEST_UPLOAD_FAILED;
        }

        return $request->id;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/deleteGJFriendRequests20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->delete($data['accountID'], Arr::wrap($data['accounts'] ?? $data['targetAccountID']), $data['isSender'] ?? false)) {
            return ResponseCode::FRIEND_REQUEST_DELETE_FAILED;
        }

        return ResponseCode::FRIEND_REQUEST_DELETE_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJFriendRequests20
     */
    public function get(GetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->list($data['accountID'], $data['getSent'] ?? false, $data['page']);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/readGJFriendRequest20
     */
    public function read(ReadRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->read($data['accountID'], $data['requestID'])) {
            return ResponseCode::FRIEND_REQUEST_READ_FAILED;

        }

        return ResponseCode::FRIEND_REQUEST_READ_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/acceptGJFriendRequest20
     */
    public function accept(AcceptFriendRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->accept($data['accountID'], $data['targetAccountID'], $data['requestID'])) {
            return ResponseCode::FRIEND_REQUEST_ACCEPT_FAILED;
        }

        return ResponseCode::FRIEND_REQUEST_ACCEPT_SUCCESS;
    }
}
