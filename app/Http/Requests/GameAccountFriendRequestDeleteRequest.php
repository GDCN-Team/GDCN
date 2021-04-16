<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountFriendRequestDeleteRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'targetAccountID' => [
                'sometimes',
                'required',
                'exclude_if:targetAccountID,0',
                Rule::exists(GameAccount::class, 'id')
            ],
            'isSender' => [
                'required',
                'boolean'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'accounts' => 'required_without:targetAccountID'
        ];
    }
}
