<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\GameAuthenticationException;
use App\Exceptions\InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameAccountLoginRequest;
use App\Http\Requests\GameAccountRegisterRequest;
use App\Http\Requests\GameRequest;
use App\Models\GameAccount;
use App\Services\WebNoticeService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * AccountsController constructor.
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
        if ($hash = $request->get('_')) {
            [$accountID, $email] = explode(':', Crypt::decryptString($hash));
            $account = GameAccount::where([
                'id' => $accountID,
                'email' => $email
            ])->first();

            if ($account->markEmailAsVerified()) {
                $this->noticeService->sendSuccessNotice(Lang::get('GameAccountEmailVerify.success'));
            } else {
                $this->noticeService->sendErrorNotice(Lang::get('GameAccountEmailVerify.failed'));
            }
        } else {
            $this->noticeService->sendErrorNotice(Lang::get('GameAccountEmailVerify.param_missing'));
        }

        return Redirect::home();
    }

    /**
     * @param GameAccountLoginRequest $request
     * @return int|string
     *
     * @throws GameAuthenticationException
     * @see http://docs.gdprogra.me/#/endpoints/accounts/loginGJAccount
     */
    public function login(GameAccountLoginRequest $request)
    {
        $data = $request->validated();
        $request->auth();

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

        return "$account->id,$user->id";
    }
}
