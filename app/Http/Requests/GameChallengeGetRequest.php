<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameChallengeGetRequest extends GameRequest
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
                'sometimes',
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required',
            'uuid' => 'required_with:udid',
            'world' => [
                'required',
                'boolean'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'chk' => 'required'
        ];
    }
}
