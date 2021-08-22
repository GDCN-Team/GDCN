<?php

namespace App\Services\Game\Level;

use App\Enums\Game\Level\CommentMode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment;
use App\Models\Game\User;
use App\Services\Game\HelperService;
use GDCN\GDObject;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function list(int $levelID, CommentMode $mode, int $page): string
    {
        $level = Level::findOrFail($levelID);
        $comments = $level->loadCount('comments')
            ->comments();

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

        $result = $comments->forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (Comment $comment) {
                /** @var User $user */
                $user = $comment->getRelationValue('account')->user;

                return implode(':', [
                    GDObject::merge([
                        2 => $comment->getRawOriginal('content'),
                        3 => $user->id,
                        4 => $comment->likes,
                        6 => $comment->id,
                        7 => $this->helper->checkSpam($comment->content),
                        9 => $comment->created_at->locale('en')->diffForHumans(syntax: true),
                        10 => $comment->percent
                    ], '~'),
                    GDObject::merge([
                        1 => $user->name,
                        9 => $user->score->icon,
                        10 => $user->score->color1,
                        11 => $user->score->color2,
                        14 => $user->score->icon_type,
                        15 => $user->score->special,
                        16 => $user->account->id
                    ], '~')
                ]);
            })->join('|');

        Log::channel('gdcn')
            ->info('[Level Comment System] Action: Get Comments', [
                'levelID' => $levelID,
                'mode' => $mode->value,
                'page' => $page
            ]);

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($level->comments_count, $page)
        ]);
    }

    public function upload(int $accountID, int $levelID, string $comment, int $percent = 0): Comment
    {
        Log::channel('gdcn')
            ->info('[Level Comment System] Action: Upload Comment', [
                'accountID' => $accountID,
                'levelID' => $levelID,
                'comment' => $comment,
                'percent' => $percent
            ]);

        return Level::findOrFail($levelID)
            ->comments()
            ->create([
                'account' => $accountID,
                'content' => $comment,
                'percent' => $percent
            ]);
    }

    public function delete(int $accountID, int $levelID, int $commentID): bool
    {
        Log::channel('gdcn')
            ->info('[Level Comment System] Action: Delete Comment', [
                'accountID' => $accountID,
                'levelID' => $levelID,
                'commentID' => $commentID
            ]);

        return Level::findOrFail($levelID)
            ->comments()
            ->where('account', $accountID)
            ->whereKey($commentID)
            ->delete();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getHistory(int $targetUserID, CommentMode $mode, int $page): ?string
    {
        $user = User::findOrFail($targetUserID);
        if (empty($user->account)) {
            return null;
        }

        $comments = $user->account
            ->loadCount('level_comments')
            ->level_comments();

        $count = $user->account->level_comments_count;
        if ($user->account->cant('viewCommentHistory', $user->account)) {
            $count = 0;
            $comments->whereKey(0);
        }

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

        $result = $comments->forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (Comment $comment) use ($user) {
                return implode(':', [
                    GDObject::merge([
                        1 => $comment->level,
                        2 => $comment->value('content'),
                        3 => $user->id,
                        4 => $comment->likes,
                        6 => $comment->id,
                        7 => $this->helper->checkSpam($comment->content),
                        9 => $comment->created_at->locale('en')->diffForHumans(null, true),
                        10 => $comment->percent
                    ], '~'),
                    GDObject::merge([
                        1 => $user->name,
                        9 => $user->score->icon,
                        10 => $user->score->color1,
                        11 => $user->score->color2,
                        14 => $user->score->icon_type,
                        15 => $user->score->special,
                        16 => $user->account->id
                    ], '~')
                ]);
            })->join('|');

        Log::channel('gdcn')
            ->info('[Level Comment System] Action: Get Comment Histories', [
                'targetUserID' => $targetUserID,
                'mode' => $mode->value,
                'page' => $page
            ]);

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($count, $page)
        ]);
    }
}
