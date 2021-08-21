<?php

namespace App\Http\Requests\Game\Reward;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class GetRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'sometimes',
                'exclude_if:accountID,0',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                new ValidateAccountCreditRule()
            ],
            'udid' => 'required',
            'uuid' => 'required',
            'rewardType' => 'between:0,2',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'chk' => 'required',
            'r1' => 'required',
            'r2' => 'required'
        ];
    }
}
