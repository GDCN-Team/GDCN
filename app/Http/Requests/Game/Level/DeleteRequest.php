<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
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
            'levelID' => Rule::exists(Level::class, 'id'),
            'secret' => Rule::in(['Wmfv2898gc9'])
        ];
    }
}
