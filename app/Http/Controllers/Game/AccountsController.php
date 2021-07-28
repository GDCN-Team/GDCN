<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Account\LoginRequest;
use App\Http\Requests\Game\Account\RegisterRequest;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Services\Game\AccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{
    public function __construct(
        public AccountService $service
    )
    {
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
        return $this->service->register($data['userName'], $data['password'], $data['email'])
            ? ResponseCode::ACCOUNT_REGISTER_SUCCESS : ResponseCode::ACCOUNT_REGISTER_FAILED;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function verify(Request $request): RedirectResponse
    {
        $string = $request->get('_');
        $string = Crypt::decryptString($string);
        [$accountID, $email] = explode(':', $string);

        return $this->service->verify($accountID, $email);
    }

    /**
     * @param LoginRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/accounts/loginGJAccount
     */
    public function login(LoginRequest $request): int|string
    {
        $data = $request->validated();
        if ($this->service->login($data['userName'], $data['password'], $data['udid'])) {
            /** @var Account $account */
            $account = Auth::user();

            if (!$account->hasVerifiedEmail()) {
                return ResponseCode::ACCOUNT_LOGIN_ACCOUNT_NOT_VERIFIED;
            }

            return $account->id . ',' . $account->user->id;
        }

        return ResponseCode::ACCOUNT_LOGIN_FAILED;
    }
}
