<?php

namespace App\Http\Requests\Game\User;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class ListGetRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'type' => 'between:0,1',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
