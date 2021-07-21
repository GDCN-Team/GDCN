<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\Request\AuthenticationException;
use App\Exceptions\Game\UserNotFoundException;
use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\User\InfoGetRequest;
use App\Http\Requests\Game\User\ListGetRequest;
use App\Http\Requests\Game\User\RequestAccessRequest;
use App\Http\Requests\Game\User\SearchRequest;
use App\Models\Game\Account;
use App\Models\Game\Account\Block;
use App\Models\Game\Account\Friend;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * @param InfoGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJUserInfo20
     */
    public function info(InfoGetRequest $request)
    {
        try {
            $data = $request->validated();
            $request->auth(true);

            try {
                $viewer = $request->user;
                if (!$viewer) {
                    return ResponseCode::USER_NOT_FOUND;
                }

                $target = Account::whereId($data['targetAccountID'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return ResponseCode::ACCOUNT_NOT_FOUND;
            }

            $viewerAccountID = $viewer->account->id ?? 0;
            $globalRank = UserScore::query()->where('stars', '<=', $target->user->score->stars ?? 0)->count();

            if ($viewerAccountID !== $data['targetAccountID'] && !empty($viewerAccountID)) {
                if ($target->friend->has($viewerAccountID)) {
                    $friendState = 1;
                } elseif (!empty($friendRequest = $target->friend_request->getInComing($viewerAccountID))) {
                    $friendState = 4;
                } elseif (!empty($friendRequest = $target->friend_request->getOutComing($viewerAccountID))) {
                    $friendState = 3;
                } else {
                    $friendState = 0;
                }
            }

            if (date('m-d') === '04-01') {
                $userInfo = [
                    1 => Str::random(),
                    2 => $target->user->id,
                    3 => 114,
                    4 => 8,
                    6 => $globalRank,
                    7 => $target->user->uuid ?: $target->id,
                    8 => 10,
                    9 => 9,
                    10 => 9,
                    11 => 8,
                    13 => 19,
                    14 => 1,
                    15 => 3,
                    16 => $target->user->uuid ?: $target->id,
                    17 => 19,
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
                    30 => 1,
                    31 => $friendState ?? 0,
                    43 => $target->user->score->acc_spider ?? 0,
                    44 => $target->setting->twitter ?? null,
                    45 => $target->setting->twitch ?? null,
                    46 => 514,
                    48 => $target->user->score->acc_explosion ?? 0,
                    49 => 2,
                    50 => $target->setting->comment_history_state ?? 0
                ];
            } else {
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
                    31 => $friendState ?? 0,
                    43 => $target->user->score->acc_spider ?? 0,
                    44 => $target->setting->twitter ?? null,
                    45 => $target->setting->twitch ?? null,
                    46 => $target->user->score->diamonds ?? 0,
                    48 => $target->user->score->acc_explosion ?? 0,
                    49 => $target->permission->mod_level ?? 0,
                    50 => $target->setting->comment_history_state ?? 0
                ];
            }

            if ($viewerAccountID === (int)$data['targetAccountID']) {
                $userInfo[38] = $target->message->count_new();
                $userInfo[39] = $target->friend_request->count_new();
                $userInfo[40] = $target->friend->count_new();
            }
            if (!empty($friendRequest)) {
                $userInfo[32] = $friendRequest->id;
                $userInfo[35] = $friendRequest->comment;
                $userInfo[37] = $friendRequest->created_at->diffForHumans(null, true);
            }

            return GDObject::merge($userInfo, ':');
        } catch (AuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        } catch (UserNotFoundException $e) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }

    /**
     * @param SearchRequest $request
     * @param Helpers $helper
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJUsers20
     */
    public function search(SearchRequest $request, Helpers $helper)
    {
        $data = $request->validated();
        $query = is_numeric($data['str']) ? User::whereId($data['str']) : User::query()
            ->where('name', 'LIKE', '%' . $data['str'] . '%');

        $count = $query->count();
        if ($count <= 0) {
            return ResponseCode::EMPTY_RESULT;
        }

        $users = $query->forPage(++$data['page'], $helper->perPage)
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

        return "{$users}#{$helper->generatePageHash($count, $data['page'])}";
    }

    /**
     * @param RequestAccessRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/requestUserAccess
     */
    public function requestAccess(RequestAccessRequest $request): int
    {
        /** @var Account $account */
        $account = $request->user();

        return $account->permission->mod_level ?? ResponseCode::FAILED;
    }

    /**
     * @param ListGetRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJUserList20
     */
    public function list(ListGetRequest $request)
    {
        $data = $request->validated();
        switch ($data['type']) {
            case 0: // Friends
                $query = Friend::query()
                    ->orWhere([
                        'account' => $data['accountID'],
                        'target_account' => $data['accountID']
                    ]);

                $count = $query->count();
                if ($count <= 0) {
                    return ResponseCode::EMPTY_RESULT;
                }

                return $query->get()
                    ->map(function (Friend $friend) use ($data) {
                        $accountID = ($friend->account === (int)$data['accountID'] ? $friend->target_account : $friend->account);
                        $new = $friend->account === $accountID ? !$friend->new = false : !$friend->target_new = false;
                        $friend->save();

                        try {
                            $account = Account::whereId($accountID)->firstOrFail();
                        } catch (ModelNotFoundException $e) {
                            return ResponseCode::ACCOUNT_NOT_FOUND;
                        }

                        return GDObject::merge([
                            1 => $account->name,
                            2 => $account->user->id,
                            9 => $account->user->score->icon ?? 0,
                            10 => $account->user->score->color1 ?? 0,
                            11 => $account->user->score->color2 ?? 3,
                            14 => $account->user->score->icon_type ?? 0,
                            15 => $account->user->score->special ?? 0,
                            16 => $account->user->uuid,
                            41 => $new ?? false
                        ], ':');
                    })->join('|');
            case 1: // Blocks
                $query = Block::whereAccount($data['accountID']);
                if ($query->count() <= 0) {
                    return ResponseCode::EMPTY_RESULT;
                }

                return $query->get()
                    ->map(function (Block $block) use ($data) {

                        try {
                            $accountID = ($block->account === (int)$data['accountID']) ? $block->target_account : $block->account;
                            $account = Account::whereId($accountID)->firstOrFail();
                        } catch (ModelNotFoundException $e) {
                            return ResponseCode::ACCOUNT_NOT_FOUND;
                        }

                        return GDObject::merge([
                            1 => $account->name,
                            2 => $account->user->id,
                            9 => $account->user->score->icon ?? 0,
                            10 => $account->user->score->color1 ?? 0,
                            11 => $account->user->score->color2 ?? 3,
                            14 => $account->user->score->icon_type ?? 0,
                            15 => $account->user->score->special ?? 0,
                            16 => $account->user->uuid
                        ], ':');
                    })->join('|');
            default:
                return ResponseCode::INVALID_REQUEST;
        }
    }
}
