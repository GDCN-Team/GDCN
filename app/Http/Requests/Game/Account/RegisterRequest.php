<?php

namespace App\Http\Requests\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class RegisterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'userName' => Rule::unique(Account::class, 'name'),
            'password' => 'required',
            'email' => Rule::unique(Account::class),
            'sID' => 'sometimes',
            'secret' => Rule::in('Wmfv3899gc9')
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
            'userName.unique' => ResponseCode::ACCOUNT_REGISTER_USERNAME_NOT_UNIQUE,
            'email.email' => ResponseCode::ACCOUNT_REGISTER_EMAIL_INVALID,
            'email.unique' => ResponseCode::ACCOUNT_REGISTER_EMAIL_NOT_UNIQUE
        ];
    }
}
