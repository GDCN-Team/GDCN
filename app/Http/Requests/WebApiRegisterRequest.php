<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class WebApiRegisterRequest extends ApiRequest
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
                'alpha_num',
                Rule::unique(GameAccount::class)
            ],
            'password' => [
                'required',
                'alpha_dash'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(GameAccount::class)
            ]
        ];
    }
}
