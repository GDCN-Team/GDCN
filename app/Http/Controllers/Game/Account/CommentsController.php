<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Comment\DeleteRequest;
use App\Http\Requests\Game\Account\Comment\GetRequest;
use App\Http\Requests\Game\Account\Comment\UploadRequest;
use App\Services\Game\Account\CommentService;

class CommentsController extends Controller
{
    /**
     * @param CommentService $service
     */
    public function __construct(
        protected CommentService $service
    )
    {
    }

    /**
     * @param GetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJAccountComments20
     */
    public function get(GetRequest $request): string
    {
        try {
            $data = $request->validated();
            return $this->service->get($data['accountID'], $data['page']);
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT_STRING;
        }
    }

    /**
     * @param UploadRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJAccComment20
     */
    public function upload(UploadRequest $request): int|string
    {
        $data = $request->validated();
        return $this->service->upload($data['chk'], $data['cType'], $data['userName'], $data['accountID'], $data['comment']) ?? ResponseCode::ACCOUNT_COMMENT_UPLOAD_FAILED;
    }

    /**
     * @param DeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJAccComment20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if ($this->service->delete($data['accountID'], $data['commentID'])) {
            return ResponseCode::ACCOUNT_COMMENT_DELETE_SUCCESS;
        } else {
            return ResponseCode::ACCOUNT_COMMENT_DELETE_FAILED;
        }
    }
}
