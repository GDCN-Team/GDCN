<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class DeleteRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'targetAccountID' => [ # Single
                'exclude_if:targetAccountID,0',
                Rule::exists(Account::class, 'id')
            ],
            'isSender' => 'boolean',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'accounts' => 'required_without:targetAccountID' # Multi
        ];
    }
}
