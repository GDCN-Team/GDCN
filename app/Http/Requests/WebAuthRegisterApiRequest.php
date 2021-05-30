<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebAuthRegisterApiRequest extends FormRequest
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
                Rule::unique(GameAccount::class)
            ],
            'password' => [
                'required',
                'same:password_confirmation'
            ],
            'password_confirmation' => 'required',
            'email' => [
                'required',
                Rule::unique(GameAccount::class)
            ]
        ];
    }
}
