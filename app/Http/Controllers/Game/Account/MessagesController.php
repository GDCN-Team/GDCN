<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Message\DeleteRequest;
use App\Http\Requests\Game\Account\Message\DownloadRequest;
use App\Http\Requests\Game\Account\Message\GetRequest;
use App\Http\Requests\Game\Account\Message\SendRequest;
use App\Services\Game\Account\MessageService;
use Illuminate\Support\Arr;

class MessagesController extends Controller
{
    public function __construct(
        protected MessageService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/uploadGJMessage20
     */
    public function send(SendRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->upload($data['accountID'], $data['toAccountID'], $data['subject'], $data['body'])) {
            return ResponseCode::MESSAGE_SENT_FAILED;
        }

        return ResponseCode::MESSAGE_SENT_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJMessages20
     */
    public function get(GetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->get($data['accountID'], $data['getSent'] ?? false, $data['page']);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/deleteGJMessages20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->delete($data['accountID'], Arr::wrap($data['messages'] ?? $data['messageID']), $data['isSender'] ?? false)) {
            return ResponseCode::MESSAGE_DELETE_FAILED;
        }

        return ResponseCode::MESSAGE_DELETE_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/downloadGJMessageo20
     */
    public function download(DownloadRequest $request): int|string
    {
        $data = $request->validated();
        if (!$messageInfo = $this->service->download($data['accountID'], $data['messageID'], $data['isSender'] ?? false)) {
            return ResponseCode::MESSAGE_DOWNLOAD_FAILED;
        }

        return $messageInfo;
    }
}
