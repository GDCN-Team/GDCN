<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\Comment\DeleteRequest;
use App\Http\Requests\Game\Account\Comment\GetRequest;
use App\Http\Requests\Game\Account\Comment\UploadRequest;
use App\Models\GameAccountComment;
use Exception;
use GDCN\ChkValidationException;
use GDCN\GDObject;
use Illuminate\Support\Carbon;

/**
 * Class AccountCommentsController
 * @package App\Http\Controllers
 */
class AccountCommentsController extends Controller
{
    /**
     * @param GetRequest $request
     * @param Helpers $helper
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJAccountComments20
     */
    public function get(GetRequest $request, Helpers $helper): string
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

        return "$comments#{$helper->generatePageHash($count, $page)}";
    }

    /**
     * @param UploadRequest $request
     * @param Helpers $helper
     * @return int|mixed|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJAccComment20
     */
    public function upload(UploadRequest $request, Helpers $helper)
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
     * @param DeleteRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJAccComment20
     */
    public function delete(DeleteRequest $request, Helpers $helper): int
    {
        try {
            return $helper->bool2result($request->comment->delete());
        } catch (Exception $e) {
            return ResponseCode::DELETE_FAILED;
        }
    }
}
