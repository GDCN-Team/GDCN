<?php

namespace App\Http\Controllers\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\CommandNotFoundException;
use App\Exceptions\Game\InvalidCommandException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Comment\DeleteRequest;
use App\Http\Requests\Game\Account\Comment\GetRequest;
use App\Http\Requests\Game\Account\Comment\UploadRequest;
use App\Services\Game\Account\CommentService;
use App\Services\Game\CommandService;

class CommentsController extends Controller
{
    public function __construct(
        protected CommentService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJAccountComments20
     */
    public function get(GetRequest $request): string
    {
        $data = $request->validated();
        return $this->service->get($data['accountID'], $data['page']);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/uploadGJAccComment20
     */
    public function upload(UploadRequest $request): int|string
    {
        $data = $request->validated();
        if (!$comment = $this->service->upload($data['accountID'], $data['comment'])) {
            return ResponseCode::ACCOUNT_COMMENT_UPLOAD_FAILED;
        }

        try {
            $commandExecuteResult = app(CommandService::class)->execute($comment);
        } catch (InvalidCommandException) {
            return $comment->id;
        } catch (CommandNotFoundException) {
            $commandExecuteResult = 'Command Not Found!';
        }

        return 'temp_0_' . $commandExecuteResult;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/deleteGJAccComment20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->delete($data['accountID'], $data['commentID'])) {
            return ResponseCode::ACCOUNT_COMMENT_DELETE_FAILED;
        }

        return ResponseCode::ACCOUNT_COMMENT_DELETE_SUCCESS;
    }
}
