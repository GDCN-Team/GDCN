<?php

namespace App\Http\Requests\Game\User;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class InfoGetRequest extends Request
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
            'uuid' => 'required_without_all:accountID,gjp',
            'udid' => 'required_with:uuid',
            'targetAccountID' => Rule::exists(Account::class, 'id'),
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
