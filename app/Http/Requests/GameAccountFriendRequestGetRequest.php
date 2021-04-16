<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountFriendRequestGetRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'gte:21'
            ],
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'page' => [
                'required',
                'integer'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'getSent' => [
                'sometimes',
                'required',
                'boolean'
            ]
        ];
    }
}
