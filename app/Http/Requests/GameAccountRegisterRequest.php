<?php

namespace App\Http\Requests;

use App\Enums\ResponseCode;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountRegisterRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'userName' => [
                'required',
                Rule::unique(GameAccount::class, 'name')
            ],
            'password' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique(GameAccount::class)
            ],
            'secret' => [
                'required',
                Rule::in('Wmfv3899gc9')
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            '*.required' => ResponseCode::INVALID_REQUEST,
            'userName.unique' => ResponseCode::ACCOUNT_REGISTER_USERNAME_IS_ALREADY_IN_USE,
            'email.email' => ResponseCode::ACCOUNT_REGISTER_EMAIL_IS_INVALID,
            'email.unique' => ResponseCode::ACCOUNT_REGISTER_EMAIL_IS_ALREADY_IN_USE
        ];
    }
}
