<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Game\Helpers;
use App\Http\Requests\GameAccountCommentDeleteRequest;
use App\Http\Requests\GameAccountCommentGetRequest;
use App\Http\Requests\GameAccountCommentUploadRequest;
use App\Models\GameAccountComment;
use Exception;
use GDCN\ChkValidationException;
use GDCN\GDObject;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountCommentsController
 * @package App\Http\Controllers
 */
class GameAccountCommentsController extends Controller
{
    /**
     * @param GameAccountCommentGetRequest $request
     * @param Helpers $helper
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJAccountComments20
     */
    public function get(GameAccountCommentGetRequest $request, Helpers $helper)
    {
        Carbon::setLocale('en');

        $data = $request->validated();
        $page = $data['page'];

        $query = $request->account->comments();
        $count = $query->count();
        if ($count <= 0) {
            return "#{$helper->generatePageHash(0, 0)}";
        }

        $comments = $query->forPage(++$page, $helper->perPage)
            ->get()
            ->map(function (GameAccountComment $comment) use ($helper) {
                return GDObject::merge([
                    2 => $comment->content,
                    4 => $comment->likes,
                    6 => $comment->id,
                    7 => $helper->checkSpam($comment->content),
                    9 => $comment->created_at->diffForHumans(null, true),
                    12 => $comment->sender->permission->comment_color ?? null
                ], '~');
            })->join('|');

        return "{$comments}#{$helper->generatePageHash($count, $page)}";
    }

    /**
     * @param GameAccountCommentUploadRequest $request
     * @param Helpers $helper
     * @return int|mixed|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJAccComment20
     */
    public function upload(GameAccountCommentUploadRequest $request, Helpers $helper)
    {
        $data = $request->validated();

        try {
            $request->validateChk();
        } catch (ChkValidationException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        }

        $comment = GameAccountComment::create([
            'account' => $data['accountID'],
            'content' => $data['comment']
        ]);

        return $helper->doCommand($comment) ?? $comment->id;
    }

    /**
     * @param GameAccountCommentDeleteRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJAccComment20
     */
    public function delete(GameAccountCommentDeleteRequest $request, Helpers $helper): int
    {
        try {
            return $helper->bool2result($request->comment->delete());
        } catch (Exception $e) {
            return ResponseCode::DELETE_FAILED;
        }
    }
}
