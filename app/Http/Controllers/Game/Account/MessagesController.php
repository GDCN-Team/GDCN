<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\NoItemException;
use App\Exceptions\Game\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Message\DeleteRequest;
use App\Http\Requests\Game\Account\Message\DownloadRequest;
use App\Http\Requests\Game\Account\Message\GetRequest;
use App\Http\Requests\Game\Account\Message\SendRequest;
use App\Services\Game\Account\MessageService;

class MessagesController extends Controller
{
    /**
     * @param MessageService $service
     */
    public function __construct(
        protected MessageService $service
    )
    {
    }

    /**
     * @param SendRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJMessage20
     */
    public function send(SendRequest $request): int
    {
        $data = $request->validated();
        if ($this->service->upload($data['accountID'], $data['toAccountID'], $data['subject'], $data['body'])) {
            return ResponseCode::MESSAGE_SENT_SUCCESS;
        } else {
            return ResponseCode::MESSAGE_SENT_FAILED;
        }
    }

    /**
     * @param GetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJMessages20
     */
    public function get(GetRequest $request): string
    {
        $data = $request->validated();
        try {
            return $this->service->get($data['accountID'], $data['page'], $data['getSent'] ?? false);
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT;
        }
    }

    /**
     * @param DeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJMessages20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if ($request->has('messages')) {
            $result = $this->service->multiDelete($data['accountID'], explode(',', $data['messages']), $data['isSender']);
        } else {
            $result = $this->service->singleDelete($data['accountID'], $data['messageID'], $data['isSender']);
        }

        if ($result) {
            return ResponseCode::MESSAGE_DELETE_SUCCESS;
        } else {
            return ResponseCode::MESSAGE_DELETE_FAILED;
        }
    }

    /**
     * @param DownloadRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/downloadGJMessageo20
     */
    public function download(DownloadRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->download($data['accountID'], $data['messageID'], $data['isSender'] ?? false);
        } catch (NoPermissionException) {
            return ResponseCode::MESSAGE_DOWNLOAD_FAILED;
        }
    }
}
