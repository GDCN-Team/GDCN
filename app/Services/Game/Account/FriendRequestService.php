<?php

namespace App\Services\Game\Account;

use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\Account\Friend;
use App\Models\Game\Account\FriendRequest;
use App\Repositories\Game\Account\FriendRequestRepository;
use App\Services\Game\HelperService;
use GDCN\GDObject;

/**
 * Class FriendRequestService
 * @package App\Services\Game\Account
 */
class FriendRequestService
{
    /**
     * FriendRequestService constructor.
     * @param HelperService $helper
     * @param FriendRequestRepository $repository
     */
    public function __construct(
        protected HelperService $helper,
        protected FriendRequestRepository $repository
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @param string $comment
     * @return FriendRequest|null
     */
    public function upload(Account|int $account, Account|int $targetAccount, string $comment): ?FriendRequest
    {
        $account = $this->helper->getModel($account, Account::class);
        $targetAccount = $this->helper->getModel($targetAccount, Account::class);

        if ($account->can('send_friend_request', $targetAccount)) {
            $request = new FriendRequest();
            $request->account = $account->id;
            $request->to_account = $targetAccount->id;
            $request->comment = $comment;
            $request->save();

            return $request;
        }

        return null;
    }

    /**
     * @param int|Account $account
     * @param array $targetAccounts
     * @param bool $isSender
     * @return bool
     */
    public function multiDelete(Account|int $account, array $targetAccounts, bool $isSender): bool
    {
        foreach ($targetAccounts as $targetAccount) {
            if (!$this->singleDelete($account, $targetAccount, $isSender)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @param bool $isSender
     * @return mixed
     */
    public function singleDelete(Account|int $account, Account|int $targetAccount, bool $isSender): mixed
    {
        $accountID = $this->helper->getID($account);
        $targetAccountID = $this->helper->getID($targetAccount);

        return $this->repository->betweenByRelation($accountID, $targetAccountID, $isSender)->delete();
    }

    /**
     * @param int|Account $account
     * @param bool $getSent
     * @param int $currentPage
     * @return string
     * @throws NoItemException
     */
    public function list(Account|int $account, bool $getSent, int $currentPage = 0): string
    {
        $this->helper->setCarbonLocaleToEnglish();
        $requests = $this->repository->byAccount($account, $getSent);

        if ($requests->count() <= 0) {
            throw new NoItemException();
        }

        return $requests->forPage(++$currentPage, $this->helper->perPage)
            ->get()
            ->map(function (FriendRequest $request) use ($getSent) {
                $account = Account::findOrFail($getSent ? $request->to_account : $request->account);

                # https://docs.gdprogra.me/#/resources/server/friendrequest
                return GDObject::merge([
                    1 => $account->name,
                    2 => $account->user->id,
                    9 => $account->user->score->icon ?? 0,
                    10 => $account->user->score->color1 ?? 0,
                    11 => $account->user->score->color2 ?? 3,
                    14 => $account->user->score->icon_type ?? 0,
                    15 => $account->user->score->special ?? 0,
                    16 => $account->id,
                    32 => $request->id,
                    35 => $request->comment,
                    37 => $request->created_at->diffForHumans(null, true),
                    41 => $request->new
                ], ':');
            })->join('|');
    }

    /**
     * @param int|Account $account
     * @param int|FriendRequest $request
     * @return bool
     */
    public function read(Account|int $account, int|FriendRequest $request): bool
    {
        $account = $this->helper->getModel($account, Account::class);
        $request = $this->helper->getModel($request, FriendRequest::class);

        if ($account->can('read', $request)) {
            $request->new = false;
            $request->save();

            return true;
        }

        return false;
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @param int|FriendRequest $request
     * @return bool|null
     */
    public function accept(Account|int $account, Account|int $targetAccount, int|FriendRequest $request): ?bool
    {
        $account = $this->helper->getModel($account, Account::class);
        $targetAccount = $this->helper->getModel($targetAccount, Account::class);
        $request = $this->helper->getModel($request, FriendRequest::class);

        if ($account->can('accept', $request) && $targetAccount->can('add', $request)) {
            $friend = new Friend();
            $friend->account = $account->id;
            $friend->target_account = $targetAccount->id;
            $friend->save();

            return $request->delete();
        }

        return false;
    }
}
