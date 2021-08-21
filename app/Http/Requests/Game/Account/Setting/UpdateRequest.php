<?php

namespace App\Http\Requests\Game\Account\Setting;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    public function rules(): array
    {
        return [
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'mS' => 'between:0,2',
            'frS' => 'between:0,1',
            'cS' => 'between:0,2',
            'yt' => 'nullable',
            'twitter' => 'nullable',
            'twitch' => 'nullable',
            'secret' => Rule::in(['Wmfv3899gc9'])
        ];
    }
}
