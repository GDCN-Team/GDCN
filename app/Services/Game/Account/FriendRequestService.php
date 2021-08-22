<?php

namespace App\Services\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Friend;
use App\Models\Game\Account\FriendRequest;
use GDCN\GDObject;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Support\Facades\Log;

class FriendRequestService
{
    public function upload(int $accountID, int $targetAccountID, string $comment): ?FriendRequest
    {
        $account = Account::findOrFail($accountID);
        $targetAccount = Account::findOrFail($targetAccountID);

        if ($account->cant('sendFriendRequest', $targetAccount)) {
            Log::channel('gdcn')
                ->notice('[Account Friend Request System] Action: Send Request Failed', [
                    'accountID' => $accountID,
                    'targetAccountID' => $targetAccountID,
                    'comment' => $comment,
                    'reason' => 'Blocked By Account Setting Policy'
                ]);

            return null;
        }

        /** @var FriendRequest $friendRequest */
        $friendRequest = $account->sent_friend_requests()
            ->firstOrCreate([
                'to_account' => $targetAccountID
            ], [
                'comment' => $comment
            ]);

        Log::channel('gdcn')
            ->info('[Account Friend Request System] Action: Send Request', [
                'accountID' => $accountID,
                'targetAccountID' => $targetAccountID,
                'comment' => $comment
            ]);

        $friendRequest->save();
        return $friendRequest;
    }

    public function delete(int $accountID, array $targetAccountIDs, bool $isSender): bool
    {
        $account = Account::findOrFail($accountID);

        if ($isSender === true) {
            $query = $account->sent_friend_requests();
            $column = 'to_account';
        } else {
            $query = $account->friend_requests();
            $column = 'account';
        }

        foreach ($targetAccountIDs as $targetAccountID) {
            Log::channel('gdcn')
                ->info('[Account Friend Request System] Action: Delete Request', [
                    'accountID' => $accountID,
                    'targetAccountID' => $targetAccountID,
                    'column' => $column,
                    'result' => $query->where($column, $targetAccountID)->delete()
                ]);
        }

        return true;
    }

    public function list(int $accountID, bool $getSent, int $page): string
    {
        $account = Account::findOrFail($accountID);
        if ($getSent === true) {
            $account->loadCount('sent_friend_requests');
            $requests = $account->sent_friend_requests();
            $column = 'to_account';
            $count = $account->sent_friend_requests_count;
        } else {
            $account->loadCount('friend_requests');
            $requests = $account->friend_requests();
            $column = 'account';
            $count = $account->friend_requests_count;
        }

        $result = $requests->forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (FriendRequest $request) use ($column, $getSent) {
                /** @var Account $account */
                $account = $request->getRelationValue($column);

                # https://docs.gdprogra.me/#/resources/server/friendrequest
                return GDObject::merge([
                    1 => $account->name,
                    2 => $account->user->id,
                    9 => $account->user->score->icon,
                    10 => $account->user->score->color1,
                    11 => $account->user->score->color2,
                    14 => $account->user->score->icon_type,
                    15 => $account->user->score->special,
                    16 => $account->id,
                    32 => $request->id,
                    35 => $request->getRawOriginal('comment'),
                    37 => $request->created_at->locale('en')->diffForHumans(syntax: true),
                    41 => $request->new
                ], ':');
            })->join('|');

        Log::channel('gdcn')
            ->info('[Account Friend Request System] Action: Get Requests', [
                'accountID' => $accountID,
                'getSent' => $getSent,
                'page' => $page
            ]);

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($count, $page)
        ]);
    }

    public function read(int $accountID, int $requestID): bool
    {
        Log::channel('gdcn')
            ->info('[Account Friend Request System] Action: Read Request', [
                'accountID' => $accountID,
                'requestID' => $requestID
            ]);

        return Account::findOrFail($accountID)
            ->friend_requests()
            ->whereKey($requestID)
            ->update([
                'new' => false
            ]);
    }

    public function accept(int $accountID, int $targetAccountID, int $requestID): ?Friend
    {
        Log::channel('gdcn')
            ->info('[Account Friend Request System] Action: Accept Request', [
                'accountID' => $accountID,
                'targetAccountID' => $targetAccountID,
                'requestID' => $requestID
            ]);

        $account = Account::findOrFail($accountID);
        $friendRequestDeleted = $account->friend_requests()
            ->where('account', $targetAccountID)
            ->whereKey($requestID)
            ->delete();

        if (!$friendRequestDeleted) {
            return null;
        }

        return $account->friends()
            ->create([
                'target_account' => $targetAccountID
            ]);
    }
}
