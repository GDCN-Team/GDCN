<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountSaveDataGetUrlRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'type' => [
                'required',
                Rule::in([1, 2])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
