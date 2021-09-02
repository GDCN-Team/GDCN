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
use Illuminate\Support\Facades\Crypt;

class AccountsController extends Controller
{
    public function __construct(
        public AccountService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/accounts/registerGJAccount
     */
    public function register(RegisterRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->register($data['userName'], $data['password'], $data['email'])) {
            return ResponseCode::ACCOUNT_REGISTER_FAILED;
        }

        return ResponseCode::ACCOUNT_REGISTER_SUCCESS;
    }

    public function verify(Request $request): RedirectResponse
    {
        $string = $request->get('_');
        $string = Crypt::decryptString($string);
        [$accountID, $email] = explode(':', $string);

        return $this->service->verify($accountID, $email);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/accounts/loginGJAccount
     */
    public function login(LoginRequest $request): int|string
    {
        $data = $request->validated();
        if (!$this->service->login($data['userName'], $data['password'], $data['udid'])) {
            return ResponseCode::ACCOUNT_LOGIN_FAILED;
        }

        $account = Account::whereName($data['userName'])->firstOrFail();
        if (empty($account->user)) {
            return ResponseCode::USER_NOT_FOUND;
        }

        if (!$account->hasVerifiedEmail()) {
            return ResponseCode::ACCOUNT_LOGIN_ACCOUNT_NOT_VERIFIED;
        }

        return $account->id . ',' . $account->user->id;
    }
}
