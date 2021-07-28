<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Traits\AuthTrait;
use App\Http\Controllers\Web\Traits\ResponseTrait;
use App\Http\Requests\Web\Api\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthTrait;
    use ResponseTrait;

    /**
     * @return array
     */
    public function checkVerified(): array
    {
        $account = $this->getAccount();

        $status = $account->hasVerifiedEmail();
        return $this->response($status);
    }

    /**
     * @param LoginRequest $request
     * @return array
     */
    public function login(LoginRequest $request): array
    {
        $data = $request->validated();
        $attempt = Auth::attempt($data, true);

        return $this->response($attempt);
    }

    /**
     * @return array
     */
    public function logout(): array
    {
        Auth::logout();
        return $this->response(true);
    }
}
