<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Services\Game\HelperService;
use GDCN\GDObject;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function __construct(
        protected HelperService $helper
    )
    {
    }

    public function get(int $accountID, int $page): string
    {
        $account = Account::findOrFail($accountID);
        $result = $account->loadCount('comments')
            ->comments()
            ->forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (AccountComment $comment) use ($account) {
                return GDObject::merge([
                    2 => $comment->content,
                    4 => $comment->likes,
                    6 => $comment->id,
                    7 => $this->helper->checkSpam($comment->content),
                    9 => $comment->created_at->locale('en')->diffForHumans(syntax: true),
                    12 => $account->permission_group?->comment_color
                ], '~');
            })->join('|');

        Log::channel('gdcn')
            ->info('[Account Comment System] Action: Get Comments', [
                'accountID' => $accountID,
                'page' => $page
            ]);

        Log::channel('debug')
            ->debug('[Account Comment System] Action: Get Comments', [
                'accountID' => $accountID,
                'page' => $page,
                'result' => $result
            ]);

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($account->comments_count, $page)
        ]);
    }

    public function upload(int $accountID, string $comment): Account\Comment
    {
        Log::channel('gdcn')
            ->info('[Account Comment System] Action: Upload Comment', [
                'accountID' => $accountID,
                'comment' => $comment
            ]);

        return Account::findOrFail($accountID)
            ->comments()
            ->create([
                'content' => $comment
            ]);
    }

    public function delete(int $accountID, int $commentID): bool
    {
        Log::channel('gdcn')
            ->info('[Account Comment System] Action: Delete Comment', [
                'accountID' => $accountID,
                'commentID' => $commentID
            ]);

        return Account::findOrFail($accountID)
            ->comments()
            ->whereKey($commentID)
            ->delete();
    }
}
