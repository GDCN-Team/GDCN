<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Exceptions\InvalidArgumentException;
use App\Http\Requests\GameAccountLoginRequest;
use App\Http\Requests\GameAccountRegisterRequest;
use App\Http\Requests\GameRequest;
use App\Models\GameAccount;
use App\Services\WebNoticeService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

/**
 * Class GameAccountsController
 * @package App\Http\Controllers
 */
class GameAccountsController extends Controller
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * GameAccountsController constructor.
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebNoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @param GameAccountRegisterRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/accounts/registerGJAccount
     */
    public function register(GameAccountRegisterRequest $request): int
    {
        /**
         * -1: Something went wrong
         * -2: Username is already in use
         * -3: Email is already in use
         * -4: Username is invalid
         * -5: Password is invalid
         * -6: Email is invalid
         * -7: Passwords do not match
         * -8: Too short. Minimum 6 characters (Password)
         * -9: Too short. Minimum 3 characters (Username)
         */

        $data = $request->validated();
        $account = GameAccount::query()
            ->create([
                'name' => $data['userName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

        event(new Registered($account));
        return ResponseCode::OK;
    }

    /**
     * @param GameRequest $request
     * @return RedirectResponse
     */
    public function verify(GameRequest $request): RedirectResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        if ($account->markEmailAsVerified()) {
            $this->noticeService->sendSuccessNotice('账号验证成功!');
        } else {
            $this->noticeService->sendErrorNotice('账号验证失败');
        }

        return Redirect::route('home');
    }

    /**
     * @param GameAccountLoginRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/accounts/loginGJAccount
     */
    public function login(GameAccountLoginRequest $request)
    {
        $data = $request->validated();

        /** @var GameAccount $account */
        $account = $request->user();

        if (!$account->hasVerifiedEmail()) {
            return ResponseCode::ACCOUNT_LOGIN_ACCOUNT_NOT_VERIFIED;
        }

        try {
            $user = $account->resolveUser($data['udid']);
        } catch (InvalidArgumentException $e) {
            return ResponseCode::USER_NOT_FOUND;
        }

        return "{$account->id},{$user->id}";
    }
}
