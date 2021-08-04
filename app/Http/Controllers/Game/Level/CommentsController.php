<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\Level\CommentMode;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\AccountNotFoundException;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\Comment\DeleteRequest;
use App\Http\Requests\Game\Level\Comment\GetRequest;
use App\Http\Requests\Game\Level\Comment\HistoryGetRequest;
use App\Http\Requests\Game\Level\Comment\UploadRequest;
use App\Services\Game\Level\CommentService;

class CommentsController extends Controller
{
    /**
     * @param CommentService $service
     */
    public function __construct(
        public CommentService $service
    )
    {
    }

    /**
     * @param GetRequest $request
     * @return int|string
     *
     * @throws InvalidArgumentException
     * @see http://docs.gdprogra.me/#/endpoints/getGJComments21
     */
    public function get(GetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->list($data['levelID'], CommentMode::fromValue((int)$data['mode']), $data['page']);
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT_STRING;
        }
    }

    /**
     * @param UploadRequest $request
     * @return int|string
     *
     * @see https://docs.gdprogra.me/#/endpoints/uploadGJComment21
     */
    public function upload(UploadRequest $request): int|string
    {
        $data = $request->validated();
        return $this->service->upload($data['accountID'], $data['levelID'], $data['comment']) ?? ResponseCode::LEVEL_COMMENT_UPLOAD_FAILED;
    }

    /**
     * @param DeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJComment20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if ($this->service->delete($data['accountID'], $data['commentID'])) {
            return ResponseCode::LEVEL_COMMENT_DELETE_SUCCESS;
        } else {
            return ResponseCode::LEVEL_COMMENT_DELETE_FAILED;
        }
    }

    /**
     * @param HistoryGetRequest $request
     * @return int|string
     *
     * @throws AccountNotFoundException
     * @throws InvalidArgumentException
     * @see http://docs.gdprogra.me/#/endpoints/getGJCommentHistory
     */
    public function history(HistoryGetRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->getHistory($data['userID'], CommentMode::fromValue((int)$data['mode']), $data['page']);
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT_STRING;
        }
    }
}
