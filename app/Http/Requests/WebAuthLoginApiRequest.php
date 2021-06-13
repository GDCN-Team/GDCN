<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebAuthLoginApiRequest extends FormRequest
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
                Rule::exists('game_accounts')
            ],
            'password' => 'required',
            'remember' => [
                'required',
                'boolean'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function messages(): array
    {
        return [
            'name.required' => '请输入用户名',
            'name.exists' => '用户不存在(或未找到)',
            'password.required' => '请输入密码'
        ];
    }
}
