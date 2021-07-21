<?php

namespace App\Http\Requests\Web\Auth;

use App\Models\Game\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterApiRequest extends FormRequest
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
                Rule::unique(Account::class)
            ],
            'password' => 'required',
            'password_confirmation' => [
                'required',
                'same:password'
            ],
            'email' => [
                'required',
                Rule::unique(Account::class)
            ]
        ];
    }
}
