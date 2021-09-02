<?php

namespace App\Services\Game;

use App\Enums\Game\FriendState;
use App\Enums\Game\UserListType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\Account\Block;
use App\Models\Game\Account\Friend;
use App\Models\Game\Account\FriendRequest;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use GDCN\GDObject;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getInfo(int $targetAccountID, ?int $viewerAccountID): string
    {
        $viewer = null;
        if (!empty($viewerAccountID)) {
            $viewer = Account::find($viewerAccountID);
        }

        $target = Account::find($targetAccountID);

        $friendState = FriendState::NONE;
        if (!empty($viewer) && !empty($target)) {
            if ($target->is($viewer)) {
                $friendState = FriendState::IS;
            }

            $isFriend = $target->friends()
                ->where([
                    'account' => $targetAccountID,
                    'target_account' => $viewerAccountID
                ])->orWhere([
                    'target_account' => $targetAccountID
                ])->where([
                    'account' => $viewerAccountID
                ])->exists();

            if ($isFriend === true) {
                $friendState = FriendState::IS;
            }

            $inComingFriendRequest = $target->friend_requests()
                ->where('account', $viewerAccountID)
                ->first();

            if (!empty($inComingFriendRequest)) {
                $friendRequest = $inComingFriendRequest;
                $friendState = FriendState::REQUEST_IN_COMING;
            }

            $outComingFriendRequest = $target->sent_friend_requests()
                ->where('to_account', $viewerAccountID)
                ->first();

            if (!empty($outComingFriendRequest)) {
                $friendRequest = $outComingFriendRequest;
                $friendState = FriendState::REQUEST_OUT_COMING;
            }
        }

        $globalRank = UserScore::where('stars', '<=', $target->user->score->stars ?? 0)->count();

        $userInfo = [
            1 => $target->user->name,
            2 => $target->user->id,
            3 => $target->user->score->stars ?? 0,
            4 => $target->user->score->demons ?? 0,
            6 => $globalRank,
            7 => $target->user->uuid ?: $target->id,
            8 => $target->user->score->creator_points ?? 0,
            9 => $target->user->score->icon ?? 0,
            10 => $target->user->score->color1 ?? 0,
            11 => $target->user->score->color2 ?? 3,
            13 => $target->user->score->coins ?? 0,
            14 => $target->user->score->icon_type ?? 0,
            15 => $target->user->score->special ?? 0,
            16 => $target->user->uuid ?: $target->id,
            17 => $target->user->score->user_coins ?? 0,
            18 => $target->setting->message_state ?? 0,
            19 => $target->setting->friend_request_state ?? 0,
            20 => $target->setting->youtube ?? null,
            21 => $target->user->score->acc_icon ?? 0,
            22 => $target->user->score->acc_ship ?? 0,
            23 => $target->user->score->acc_ball ?? 0,
            24 => $target->user->score->acc_bird ?? 0,
            25 => $target->user->score->acc_dart ?? 0,
            26 => $target->user->score->acc_robot ?? 0,
            28 => $target->user->score->acc_glow ?? 0,
            29 => true,
            30 => $globalRank,
            31 => $friendState,
            43 => $target->user->score->acc_spider ?? 0,
            44 => $target->setting->twitter ?? null,
            45 => $target->setting->twitch ?? null,
            46 => $target->user->score->diamonds ?? 0,
            48 => $target->user->score->acc_explosion ?? 0,
            49 => $target->permission->mod_level ?? 0,
            50 => $target->setting->comment_history_state ?? 0
        ];

        if (!empty($friendRequest)) {
            /** @var FriendRequest $friendRequest */

            $userInfo[32] = $friendRequest->id;
            $userInfo[35] = $friendRequest->comment;
            $userInfo[37] = $friendRequest->created_at->diffForHumans(syntax: true);
        }

        if ($target->is($viewer)) {
            $userInfo[38] = $target->messages()
                ->where('readed', false)
                ->count();

            $userInfo[39] = $target->friend_requests()
                ->where('new', true)
                ->count();

            $userInfo[40] = $target->friends()
                ->where([
                    'account' => $targetAccountID,
                    'new' => true
                ])->orWhere([
                    'target_account' => $targetAccountID
                ])->where([
                    'target_new' => true
                ])->count();
        }

        Log::channel('gdcn')
            ->info('[User System] Action: Get Info', [
                'viewerAccountID' => $viewerAccountID,
                'targetAccountID' => $targetAccountID
            ]);

        return GDObject::merge($userInfo, ':');
    }

    public function search(string $str, int $page): string
    {
        $users = User::whereKey($str)
            ->orWhere('name', 'LIKE', '%' . $str . '%');

        $result = $users->forPage(++$page, PageInfoComponent::$per_page)
            ->with('score')
            ->get()
            ->map(function (User $user) {
                return GDObject::merge([
                    1 => $user->name,
                    2 => $user->id,
                    3 => $user->score->stars,
                    4 => $user->score->demons,
                    8 => $user->score->creator_points,
                    9 => $user->score->icon,
                    10 => $user->score->color1,
                    11 => $user->score->color2,
                    13 => $user->score->coins,
                    14 => $user->score->icon_type,
                    15 => $user->score->special,
                    16 => $user->uuid,
                    17 => $user->score->user_coins
                ], ':');
            })->join('|');

        Log::channel('gdcn')
            ->info('[User System] Action: Search Users', [
                'str' => $str,
                'page' => $page
            ]);

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($users->count(), $page)
        ]);
    }

    public function requestAccess(int $accountID): int
    {
        Log::channel('gdcn')
            ->info('[User System] Action: Request Access', [
                'accountID' => $accountID
            ]);

        return Account::find($accountID)
            ->permission_group
            ?->mod_level;
    }

    /**
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function list(int $accountID, UserListType $type): string
    {
        $account = Account::find($accountID);

        $query = match ($type->value) {
            UserListType::FRIENDS => $account->friends(),
            UserListType::BLOCKS => $account->blocks(),
            default => throw new InvalidArgumentException()
        };

        if ($query->count() <= 0) {
            throw new NoItemException();
        }

        Log::channel('gdcn')
            ->info('[User System] Action: Get Users', [
                'accountID' => $accountID,
                'type' => $type->value
            ]);

        return $query
            ->get()
            ->map(function ($data) use ($accountID, $type) {
                /** @var Friend|Block $data */

                $new = false;
                if ($type->value === UserListType::FRIENDS) {
                    $account = $data->getRelationValue($data->account == $accountID ? 'target_account' : 'account');
                    $new = $data->account == $accountID ? ($data->new = false) : ($data->target_new = false);
                    $data->save();
                } else {
                    $account = $data->getRelationValue('target_account');
                }

                /** @var Account $account */

                $object = [
                    1 => $account->name,
                    2 => $account->user->id,
                    9 => $account->user->score->icon,
                    10 => $account->user->score->color1,
                    11 => $account->user->score->color2,
                    14 => $account->user->score->icon_type,
                    15 => $account->user->score->special,
                    16 => $account->user->uuid
                ];

                if ($type->value === UserListType::FRIENDS) {
                    $object[41] = $new;
                }

                return GDObject::merge($object, ':');
            })->join('|');
    }
}
