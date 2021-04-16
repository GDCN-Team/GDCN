<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Exceptions\GameAuthenticationException;
use App\Game\Helpers;
use App\Http\Requests\GameAccountFriendRequestAcceptRequest;
use App\Http\Requests\GameAccountFriendRequestDeleteRequest;
use App\Http\Requests\GameAccountFriendRequestGetRequest;
use App\Http\Requests\GameAccountFriendRequestReadRequest;
use App\Http\Requests\GameAccountFriendRequestUploadRequest;
use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;
use Exception;
use GDCN\GDObject;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * Class GameAccountFriendRequestsController
 * @package App\Http\Controllers
 */
class GameAccountFriendRequestsController extends Controller
{
    /**
     * @param GameAccountFriendRequestUploadRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadFriendRequest20
     */
    public function upload(GameAccountFriendRequestUploadRequest $request): int
    {
        try {
            $data = $request->validated();
            $request->auth();

            try {
                /** @var GameAccount $receiver */
                $receiver = GameAccount::whereId($data['toAccountID'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return ResponseCode::ACCOUNT_NOT_FOUND;
            }

            if (!$this->authorize('send_friend_request', $receiver)) {
                return ResponseCode::PERMISSION_DENIED;
            }

            GameAccountFriendRequest::query()
                ->firstOrCreate([
                    'account' => $data['accountID'],
                    'to_account' => $receiver->id
                ], [
                    'comment' => $data['comment']
                ]);

            return ResponseCode::OK;
        } catch (GameAuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        } catch (AuthorizationException $e) {
            return ResponseCode::PERMISSION_DENIED;
        }
    }

    /**
     * @param GameAccountFriendRequestDeleteRequest $request
     * @param GameAccountFriendRequest $friendRequest
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJFriendRequests20
     */
    public function delete(GameAccountFriendRequestDeleteRequest $request, GameAccountFriendRequest $friendRequest): int
    {
        try {
            $data = $request->validated();
            $request->auth();

            try {
                if (!empty($data['targetAccountID'])) {
                    $operator = $request->user();
                    $friendRequest->findEach($operator->id, $data['targetAccountID'])->delete();

                    return ResponseCode::OK;
                }

                if (!empty($data['accounts'])) {
                    $operator = $request->user();
                    foreach (explode(',', $data['accounts']) as $account) {
                        $friendRequest->findEach($operator->id, $account)->delete();
                    }

                    return ResponseCode::OK;
                }

                return ResponseCode::FAILED;
            } catch (ModelNotFoundException $e) {
                return ResponseCode::ACCOUNT_NOT_FOUND;
            }

        } catch (GameAuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        }
    }

    /**
     * @param GameAccountFriendRequestGetRequest $request
     * @param Helpers $helper
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJFriendRequests20
     */
    public function get(GameAccountFriendRequestGetRequest $request, Helpers $helper)
    {
        try {
            $data = $request->validated();
            $page = $data['page'];

            $request->auth();

            /** @var GameAccount $operator */
            $operator = $request->user();

            $column = ($data['getSent'] ?? false) ? 'account' : 'to_account';
            $query = $operator->friend_requests->where($column, $operator->id);

            $count = $query->count();
            if ($count <= 0) {
                return ResponseCode::EMPTY_RESULT;
            }

            $requests = $query->forPage(++$page, $helper->perPage)
                ->map(function (GameAccountFriendRequest $request) {

                    try {
                        $accountID = ($data['getSent'] ?? false) ? $request->to_account : $request->account;
                        $account = GameAccount::whereId($accountID)->firstOrFail();
                    } catch (ModelNotFoundException $e) {
                        return ResponseCode::FAILED;
                    }

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
                })->toArray();

            $result = implode('|', $requests);
            return "{$result}#{$helper->generatePageHash($count, $page)}";
        } catch (GameAuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        }
    }

    /**
     * @param GameAccountFriendRequestReadRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/readGJFriendRequest20
     */
    public function read(GameAccountFriendRequestReadRequest $request): int
    {
        try {
            $data = $request->validated();
            $request->auth();

            try {
                $operator = $request->user();

                /** @var GameAccountFriendRequest $request */
                $request = GameAccountFriendRequest::whereId($data['requestID'])->firstOrFail();
                if ($operator->can('read', $request)) {
                    $request->new = false;
                    $request->save();
                    return ResponseCode::OK;
                }

                return ResponseCode::FAILED;
            } catch (ModelNotFoundException $e) {
                return ResponseCode::FRIEND_REQUEST_NOT_FOUND;
            }

        } catch (ValidationException $e) {
            return ResponseCode::REQUEST_CHECK_FAILED;
        } catch (GameAuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        }
    }

    /**
     * @param GameAccountFriendRequestAcceptRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/acceptGJFriendRequest20
     */
    public function accept(GameAccountFriendRequestAcceptRequest $request): int
    {
        try {
            $data = $request->validated();
            $request->auth();

            try {
                /** @var GameAccountFriendRequest $request */
                $request = GameAccountFriendRequest::whereId($data['requestID'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return ResponseCode::FRIEND_REQUEST_NOT_FOUND;
            }

            if ($this->authorize('accept', $request)) {
                try {
                    $request->accept();
                    return ResponseCode::OK;
                } catch (Exception $e) {
                    return ResponseCode::UNHANDLED_EXCEPTION;
                }
            }

            return ResponseCode::FAILED;
        } catch (GameAuthenticationException $e) {
            return ResponseCode::LOGIN_FAILED;
        } catch (AuthorizationException $e) {
            return ResponseCode::PERMISSION_DENIED;
        }
    }
}
