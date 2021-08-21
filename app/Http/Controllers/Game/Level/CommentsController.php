<?php

namespace App\Http\Controllers\Game\Level;

use App\Enums\Game\Level\CommentMode;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\CommandNotFoundException;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\InvalidCommandException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\Comment\DeleteRequest;
use App\Http\Requests\Game\Level\Comment\GetRequest;
use App\Http\Requests\Game\Level\Comment\HistoryGetRequest;
use App\Http\Requests\Game\Level\Comment\UploadRequest;
use App\Services\Game\CommandService;
use App\Services\Game\Level\CommentService;

class CommentsController extends Controller
{
    public function __construct(
        public CommentService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJComments21
     * @throws InvalidArgumentException
     */
    public function get(GetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->list($data['levelID'], CommentMode::fromValue($data['mode']), $data['page']);
    }

    /**
     * @link https://docs.gdprogra.me/#/endpoints/uploadGJComment21
     */
    public function upload(UploadRequest $request): int|string
    {
        $data = $request->validated();
        if (!$comment = $this->service->upload($data['accountID'], $data['levelID'], $data['comment'])) {
            return ResponseCode::LEVEL_COMMENT_UPLOAD_FAILED;
        }

        try {
            $commandExecuteResult = app(CommandService::class)->execute($comment);
        } catch (CommandNotFoundException) {
            $commandExecuteResult = 'Command Not Found!';
        } catch (InvalidCommandException) {
            return $comment->id;
        }

        return 'temp_0_' . $commandExecuteResult;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/deleteGJComment20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->delete($data['accountID'], $data['levelID'], $data['commentID'])) {
            return ResponseCode::LEVEL_COMMENT_DELETE_FAILED;
        }

        return ResponseCode::LEVEL_COMMENT_DELETE_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJCommentHistory
     * @throws InvalidArgumentException
     */
    public function history(HistoryGetRequest $request): int|string
    {
        $data = $request->validated();
        if (!$result = $this->service->getHistory($data['userID'], CommentMode::fromValue($data['mode']), $data['page'])) {
            return ResponseCode::ACCOUNT_COMMENT_HISTORY_GET_FAILED;
        }

        return $result;
    }
}
