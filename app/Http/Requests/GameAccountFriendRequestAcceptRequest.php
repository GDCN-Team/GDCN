<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;
use Illuminate\Validation\Rule;

class GameAccountFriendRequestAcceptRequest extends GameRequest
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
            'targetAccountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'requestID' => [
                'required',
                Rule::exists(GameAccountFriendRequest::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
