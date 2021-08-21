<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class UpdateDescRequest extends Request
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
            'levelID' => Rule::exists(Level::class, 'id'),
            'levelDesc' => 'nullable',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
