<?php

namespace App\Http\Requests\Game\User\Score;

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
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                new ValidateAccountCreditRule()
            ],
            'udid' => 'required_without_all:accountID,gjp',
            'uuid' => 'required_with:udid',
            'type' => Rule::in(['top', 'friends', 'relative', 'creators']),
            'count' => 'integer',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
