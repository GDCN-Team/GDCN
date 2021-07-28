<?php

namespace App\Services\Game\Level;

use App\Enums\Game\Account\Setting\CommentHistoryState;
use App\Enums\Game\Level\CommentMode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Exceptions\Game\UserNotFoundException;
use App\Models\Game\Account;
use App\Models\Game\Level\Comment;
use App\Services\Game\HelperService;
use GDCN\GDObject;

/**
 * Class CommentService
 * @package App\Services\Game\Level
 */
class CommentService
{
    public function __construct(
        public Comment $model,
        public HelperService $helper
    )
    {
    }

    /**
     * @param $level
     * @param CommentMode $mode
     * @param int $page
     * @return string
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function list($level, CommentMode $mode, int $page): string
    {
        $comments = $this->model->whereLevel($level);
        switch ($mode->value) {
            case CommentMode::RECENT:
                $comments->orderByDesc('created_at');
                break;
            case CommentMode::MOST_LIKED:
                $comments->orderByDesc('likes');
                break;
            default:
                throw new InvalidArgumentException();
        }

        $count = $comments->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        return $comments->forPage(++$page, $this->helper->perPage)
                ->get()
                ->map(function (Comment $comment) {
                    if (!$user = $comment->sender->user) {
                        throw new UserNotFoundException();
                    }

                    $this->helper->setCarbonLocaleToEnglish();
                    return implode(':', [
                        GDObject::merge([
                            2 => $comment->content,
                            3 => $comment->sender->user->id,
                            4 => $comment->likes,
                            6 => $comment->id,
                            7 => $this->helper->checkSpam($comment->content),
                            9 => $comment->created_at->diffForHumans(null, true),
                            10 => $comment->percent
                        ], '~'),
                        GDObject::merge([
                            1 => $user->name,
                            9 => $user->score->icon ?? 0,
                            10 => $user->score->color1 ?? 0,
                            11 => $user->score->color2 ?? 3,
                            14 => $user->score->icon_type ?? 0,
                            15 => $user->score->special ?? 0,
                            16 => $user->account->id
                        ], '~')
                    ]);
                })->join('|') . $this->helper->generatePageHash($count, $page);
    }

    /**
     * @param $account
     * @param $level
     * @param string $comment
     * @return Comment
     */
    public function upload($account, $level, string $comment): Comment
    {
        $accountID = $this->helper->getID($account);
        $levelID = $this->helper->getID($level);

        $commentModel = new Comment();
        $commentModel->account = $accountID;
        $commentModel->level = $levelID;
        $commentModel->content = $comment;
        $commentModel->save();

        return $commentModel;
    }

    /**
     * @param $account
     * @param $comment
     * @return bool
     */
    public function delete($account, $comment): bool
    {
        $account = $this->helper->getModel($account, Account::class);
        $comment = $this->helper->getModel($comment, Comment::class);

        if ($account->can('delete', $comment)) {
            return $comment->delete();
        }

        return false;
    }

    /**
     * @param $target
     * @param CommentMode $mode
     * @param int $page
     * @return string
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function getHistory($target, CommentMode $mode, int $page): string
    {
        /** @var Account $target */
        $target = $this->helper->getModel($target, Account::class);
        $comments = $this->model->whereAccount($target->id);

        switch ($mode->value) {
            case CommentMode::RECENT:
                $comments->orderByDesc('created_at');
                break;
            case CommentMode::MOST_LIKED:
                $comments->orderByDesc('likes');
                break;
            default:
                throw new InvalidArgumentException();
        }

        if (optional($target->setting->comment_history_state ?? null)->is(CommentHistoryState::NONE)) {
            return throw new NoItemException();
        }

        $count = $comments->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        return $comments->forPage(++$page, $this->helper->perPage)
                ->get()
                ->map(function (Comment $comment) {
                    if (!$user = $comment->sender->user) {
                        throw new UserNotFoundException();
                    }

                    $this->helper->setCarbonLocaleToEnglish();
                    return implode(':', [
                        GDObject::merge([
                            1 => $comment->level,
                            2 => $comment->content,
                            3 => $comment->sender->user->id,
                            4 => $comment->likes,
                            6 => $comment->id,
                            7 => $this->helper->checkSpam($comment->content),
                            9 => $comment->created_at->diffForHumans(null, true),
                            10 => $comment->percent
                        ], '~'),
                        GDObject::merge([
                            1 => $user->name,
                            9 => $user->score->icon ?? 0,
                            10 => $user->score->color1 ?? 0,
                            11 => $user->score->color2 ?? 3,
                            14 => $user->score->icon_type ?? 0,
                            15 => $user->score->special ?? 0,
                            16 => $user->account->id
                        ], '~')
                    ]);
                })->join('|') . $this->helper->generatePageHash($count, $page);
    }
}
