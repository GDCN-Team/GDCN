<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\LoginRequest;
use App\Http\Requests\Game\Account\RegisterRequest;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Services\Web\NoticeService;
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
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * AccountsController constructor.
     * @param NoticeService $noticeService
     */
    public function __construct(NoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @param RegisterRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/accounts/registerGJAccount
     */
    public function register(RegisterRequest $request): int
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
        $account = Account::query()
            ->create([
                'name' => $data['userName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

        event(new Registered($account));
        return ResponseCode::OK;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function verify(Request $request): RedirectResponse
    {
        if ($hash = $request->get('_')) {
            [$accountID, $email] = explode(':', Crypt::decryptString($hash));
            $account = Account::where([
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
     * @param LoginRequest $request
     * @return int|string
     *
     * @throws AuthenticationException
     * @see http://docs.gdprogra.me/#/endpoints/accounts/loginGJAccount
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
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
