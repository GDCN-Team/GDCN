<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WebDashboardUpdateAccountSettingApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    /** @var GameAccount $account */
                    $account = Auth::user();

                    if ($value !== $account->name && GameAccount::whereName($value)->exists()) {
                        $fail('用户名 ' . $attribute . ' 已被使用');
                    }
                }
            ],
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    /** @var GameAccount $account */
                    $account = Auth::user();

                    if ($value !== $account->email && GameAccount::whereEmail($value)->exists()) {
                        $fail('邮箱 ' . $attribute . ' 已被使用');
                    }
                }
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
