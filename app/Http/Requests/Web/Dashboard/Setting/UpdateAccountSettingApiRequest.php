<?php

namespace App\Http\Requests\Web\Dashboard\Setting;

use App\Models\Game\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateAccountSettingApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $accountID = Auth::id();

        return [
            'name' => [
                'required',
                Rule::unique(Account::class)->ignore($accountID)
            ],
            'email' => [
                'required',
                Rule::unique(Account::class)->ignore($accountID)
            ],
            'password_confirmation' => [
                'required',
                'password'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function messages(): array
    {
        return [
            'name.required' => '请输入新用户名',
            'email.required' => '请输入新邮箱',
            'password_confirmation.required' => '请输入密码以确认身份',
            'password_confirmation.password' => '密码错误'
        ];
    }
}
