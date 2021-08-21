<?php

namespace App\Http\Requests\Game\Account\Friend;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class RemoveRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'targetAccountID' => Rule::exists(Account::class, 'id'),
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
