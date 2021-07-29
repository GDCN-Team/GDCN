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
use App\Models\Game\Account\Message;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use App\Repositories\Game\Account\FriendRepository;
use GDCN\GDObject;

/**
 * Class UserService
 * @package App\Services\Game
 */
class UserService
{
    public function __construct(
        public HelperService $helper,
        public FriendRepository $friendRepository
    )
    {
    }

    /**
     * @param $target
     * @param null $viewer
     * @return string
     */
    public function getInfo($target, $viewer = null): string
    {
        if ($viewer) {
            $viewer = $this->helper->getModel($viewer, Account::class);
        }

        $target = $this->helper->getModel($target, Account::class);
        $globalRank = UserScore::where('stars', '<=', $target->user->score->stars ?? 0)->count();

        $friendState = FriendState::NONE;
        if ($viewer && $target) {
            if ($target->is($viewer)) { // self
                $friendState = FriendState::IS;
            }

            if ($this->friendRepository->between($viewer, $target)) { // is friend
                $friendState = FriendState::IS;
            }

            $query = FriendRequest::where(['account' => $viewer->id, 'to_account' => $target->id]);
            if ($query->exists()) {
                $friendRequest = $query->first();
                $friendState = FriendState::REQUEST_IN_COMING;
            }

            $query = FriendRequest::where(['account' => $target->id, 'to_account' => $viewer->id]);
            if ($query->exists()) {
                $friendRequest = $query->first();
                $friendState = FriendState::REQUEST_OUT_COMING;
            }
        }

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

        if ($target->is($viewer)) { // View self
            $userInfo[38] = Message::where(['to_account' => $target->id, 'readed' => false])->count();
            $userInfo[39] = FriendRequest::where(['to_account' => $target->id, 'new' => true])->count();
            $userInfo[40] = Friend::where(['target_account' => $target->id, 'target_new' => 'true'])
                ->orWhere(['account' => $target->id, 'new' => true])
                ->count();
        }

        $this->helper->setCarbonLocaleToEnglish();
        if (!empty($friendRequest)) {
            $userInfo[32] = $friendRequest->id;
            $userInfo[35] = $friendRequest->comment;
            $userInfo[37] = $friendRequest->created_at->diffForHumans(null, true);
        }

        return GDObject::merge($userInfo, ':');
    }

    /**
     * @param string $str
     * @param int $page
     * @return string
     * @throws NoItemException
     */
    public function search(string $str, int $page): string
    {
        $query = is_numeric($str) ? User::whereId($str) : User::where('name', 'LIKE', '%' . $str . '%');
        $count = $query->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        $users = $query->forPage(++$page, $this->helper->perPage)
            ->with('score')
            ->get()
            ->map(function (User $user) {
                return GDObject::merge([
                    1 => $user->name,
                    2 => $user->id,
                    3 => $user->score->stars ?? 0,
                    4 => $user->score->demons ?? 0,
                    8 => $user->score->creator_points ?? 0,
                    9 => $user->score->icon ?? 0,
                    10 => $user->score->color1 ?? 0,
                    11 => $user->score->color2 ?? 3,
                    13 => $user->score->coins ?? 0,
                    14 => $user->score->icon_type ?? 0,
                    15 => $user->score->special ?? 0,
                    16 => $user->uuid,
                    17 => $user->score->user_coins ?? 0
                ], ':');
            })->join('|');

        return $users . '#' . $this->helper->generatePageHash($count, $page);
    }

    /**
     * @param $account
     * @return int
     */
    public function requestAccess($account): int
    {
        $account = $this->helper->getModel($account, Account::class);
        return $account->permission->mod_level;
    }

    /**
     * @param $account
     * @param UserListType $type
     * @return string
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function list($account, UserListType $type): string
    {
        $accountID = $this->helper->getID($account);

        $query = match ($type->value) {
            UserListType::FRIENDS => Friend::orWhere(['account' => $accountID, 'target_account' => $accountID]),
            UserListType::BLOCKS => Block::where(['account' => $accountID]),
            default => throw new InvalidArgumentException()
        };

        if ($query->count() <= 0) {
            throw new NoItemException();
        }

        return $query
            ->get()
            ->map(function ($data) use ($accountID, $type) {
                $account = $data->account === $accountID ? $this->helper->getModel($data->target_account, Account::class) : $this->helper->getModel($data->account, Account::class);
                if ($type->value === UserListType::FRIENDS) {
                    $new = $data->account === $accountID ? ($data->new = false) : ($data->target_new = false);
                    $data->save();
                } else {
                    $new = false;
                }

                $object = [
                    1 => $account->name,
                    2 => $account->user->id,
                    9 => $account->user->score->icon ?? 0,
                    10 => $account->user->score->color1 ?? 0,
                    11 => $account->user->score->color2 ?? 3,
                    14 => $account->user->score->icon_type ?? 0,
                    15 => $account->user->score->special ?? 0,
                    16 => $account->user->uuid
                ];

                if ($type->value === UserListType::FRIENDS) {
                    $object[41] = (int)$new;
                }

                return GDObject::merge($object, ':');
            })->join('|');
    }
}
