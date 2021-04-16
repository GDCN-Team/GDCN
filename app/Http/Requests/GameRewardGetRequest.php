<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameRewardGetRequest extends GameRequest
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
                'exclude_if:accountID,0',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable'
            ],
            'udid' => 'required',
            'uuid' => 'required_with:udid',
            'rewardType' => [
                'required',
                Rule::in([0, 1, 2])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'chk' => 'required',
            'r1' => 'required',
            'r2' => 'required'
        ];
    }
}
