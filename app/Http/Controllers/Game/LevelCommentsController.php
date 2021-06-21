<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\AccountSettingCommentHistoryStateType;
use App\Enums\Game\LevelCommentType;
use App\Enums\Game\ResponseCode;
use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameLevelCommentDeleteRequest;
use App\Http\Requests\GameLevelCommentGetRequest;
use App\Http\Requests\GameLevelCommentHistoryGetRequest;
use App\Http\Requests\GameLevelCommentUploadRequest;
use App\Models\GameLevelComment;
use Exception;
use GDCN\ChkValidationException;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

/**
 * Class LevelCommentsController
 * @package App\Http\Controllers
 */
class LevelCommentsController extends Controller
{
    /**
     * @param GameLevelCommentGetRequest $request
     * @param Helpers $helper
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJComments21
     */
    public function get(GameLevelCommentGetRequest $request, Helpers $helper)
    {
        try {
            $data = $request->validated();
            $page = $data['page'];

            Carbon::setLocale('en');
            $query = GameLevelComment::whereLevel($data['levelID']);
            switch ($data['mode']) {
                case LevelCommentType::RECENT:
                    $query->orderByDesc('id');
                    break;
                case LevelCommentType::MOST_LIKED:
                    $query->orderByDesc('likes');
                    break;
                default:
                    return ResponseCode::INVALID_REQUEST;
            }

            $count = $query->count();
            if ($count <= 0) {
                return ResponseCode::EMPTY_RESULT;
            }

            $comments = $query->with(['sender', 'sender.user'])
                ->forPage(++$page, $helper->perPage)
                ->get()
                ->map(function (GameLevelComment $comment) use ($helper) {
                    if (!$user = $comment->sender->user) {
                        return ResponseCode::USER_NOT_FOUND;
                    }

                    $commentInfo = GDObject::merge([
                        2 => $comment->content,
                        3 => $comment->sender->user->id,
                        4 => $comment->likes,
                        6 => $comment->id,
                        7 => $helper->checkSpam($comment->content),
                        9 => $comment->created_at->diffForHumans(null, true),
                        10 => $comment->percent
                    ], '~');

                    $userInfo = GDObject::merge([
                        1 => $user->name,
                        9 => $user->score->icon ?? 0,
                        10 => $user->score->color1 ?? 0,
                        11 => $user->score->color2 ?? 3,
                        14 => $user->score->icon_type ?? 0,
                        15 => $user->score->special ?? 0,
                        16 => $user->account->id
                    ], '~');

                    return "{$commentInfo}:{$userInfo}";
                })->toArray();

            $result = implode('|', $comments);
            return "{$result}#{$helper->generatePageHash($count, $page)}";
        } catch (ModelNotFoundException $e) {
            return ResponseCode::LEVEL_NOT_FOUND;
        }
    }

    /**
     * @param GameLevelCommentUploadRequest $request
     * @param Helpers $helper
     * @return int|mixed|string
     */
    public function upload(GameLevelCommentUploadRequest $request, Helpers $helper)
    {
        $data = $request->validated();

        try {
            $request->validateChk();
        } catch (ChkValidationException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        }

        $comment = GameLevelComment::create([
            'level' => $data['levelID'],
            'account' => $data['accountID'],
            'content' => $data['comment']
        ]);

        return $helper->doCommand($comment) ?? $comment->id;
    }

    /**
     * @param GameLevelCommentDeleteRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJComment20
     */
    public function delete(GameLevelCommentDeleteRequest $request, Helpers $helper): int
    {
        try {
            return $helper->bool2result($request->comment->delete());
        } catch (Exception $e) {
            return ResponseCode::DELETE_FAILED;
        }
    }

    /**
     * @param GameLevelCommentHistoryGetRequest $request
     * @param Helpers $helper
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJCommentHistory
     */
    public function history(GameLevelCommentHistoryGetRequest $request, Helpers $helper)
    {
        $data = $request->validated();
        $page = $data['page'];

        if (optional($request->target->setting->comment_history_state ?? null)->is(AccountSettingCommentHistoryStateType::NONE) ?? false) {
            return ResponseCode::PERMISSION_DENIED;
        }

        Carbon::setLocale('en');
        $query = GameLevelComment::whereAccount($request->target->id);
        switch ($data['mode']) {
            case LevelCommentType::RECENT:
                $query->orderByDesc('created_at');
                break;
            case LevelCommentType::MOST_LIKED:
                $query->orderByDesc('likes');
                break;
            default:
                return ResponseCode::INVALID_REQUEST;
        }

        $count = $query->count();
        if ($count <= 0) {
            return ResponseCode::EMPTY_RESULT;
        }

        $comments = $query->forPage(++$page, $data['count'] ?? $helper->perPage)
            ->get()
            ->map(function (GameLevelComment $comment) use ($helper) {
                $commentInfo = GDObject::merge([
                    1 => $comment->level,
                    2 => $comment->content,
                    3 => $comment->account,
                    4 => $comment->likes,
                    6 => $comment->id,
                    7 => $helper->checkSpam($comment->content),
                    9 => $comment->created_at->diffForHumans(null, true),
                    10 => $comment->percent,
                    12 => $comment->sender->permission->comment_color ?? null
                ], '~');

                $userInfo = GDObject::merge([
                    1 => $comment->sender->name,
                    9 => $comment->sender->user->score->icon ?? 0,
                    10 => $comment->sender->user->score->color1 ?? 0,
                    11 => $comment->sender->user->score->color2 ?? 3,
                    14 => $comment->sender->user->score->icon_type ?? 0,
                    15 => $comment->sender->user->score->special ?? 0,
                    16 => $comment->sender->id
                ], '~');

                return "{$commentInfo}:{$userInfo}";
            })->join('|');

        return "{$comments}#{$helper->generatePageHash($count, $page)}";
    }
}
