<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class WebApiAccountSettingUpdateRequest extends ApiRequest
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
            'email' => [
                'required',
                Rule::unique(GameAccount::class)
            ]
        ];
    }
}
