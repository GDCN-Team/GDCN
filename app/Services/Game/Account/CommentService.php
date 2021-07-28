<?php

namespace App\Services\Game\Account;

use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Repositories\Game\Account\CommentRepository as AccountCommentRepository;
use App\Services\Game\HelperService;
use GDCN\GDObject;
use GDCN\Hash\Hasher;

/**
 * Class CommentService
 * @package App\Services\Game\Account
 */
class CommentService
{
    /**
     * CommentService constructor.
     * @param AccountCommentRepository $repository
     * @param Hasher $hash
     * @param HelperService $helper
     */
    public function __construct(
        protected AccountCommentRepository $repository,
        protected Hasher $hash,
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int $currentPage
     * @return string
     * @throws NoItemException
     */
    public function get(Account|int $account, int $currentPage): string
    {
        $accountID = $this->helper->getID($account);
        $comments = $this->repository->byAccount($accountID);

        $count = $comments->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        $this->helper->setCarbonLocaleToEnglish();
        return $comments->forPage(++$currentPage, $this->helper->perPage)
                ->get()
                ->map(function (AccountComment $comment) {
                    return GDObject::merge([
                        2 => $comment->content,
                        4 => $comment->likes,
                        6 => $comment->id,
                        7 => $this->helper->checkSpam($comment->content),
                        9 => $comment->created_at->diffForHumans(null, true),
                        12 => $comment->sender->permission->comment_color ?? null
                    ], '~');
                })->join('|') .
            '#' . $this->helper->generatePageHash($count, $currentPage);
    }

    /**
     * @param string $chk
     * @param int $cType
     * @param string $userName
     * @param int|Account $uploader
     * @param string $comment
     * @return AccountComment|null
     */
    public function upload(string $chk, int $cType, string $userName, Account|int $uploader, string $comment): ?AccountComment
    {
        $uploader = $this->helper->getModel($uploader, Account::class);
        if (true /*$this->hash->checkUploadAccountCommentChk($chk, $cType, $userName, $comment)*/) {
            $commentModel = new AccountComment();
            $commentModel->account = $uploader->id;
            $commentModel->content = $comment;
            $commentModel->save();

            return $commentModel;
        }

        return null;
    }

    /**
     * @param int|Account $account
     * @param int|AccountComment $comment
     * @return bool|null
     */
    public function delete(Account|int $account, AccountComment|int $comment): ?bool
    {
        $account = $this->helper->getModel($account, Account::class);
        $comment = $this->helper->getModel($comment, AccountComment::class);

        if ($account->can('delete', $comment)) {
            return $comment->delete();
        }

        return false;
    }
}
