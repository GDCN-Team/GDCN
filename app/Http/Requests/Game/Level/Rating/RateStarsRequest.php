<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Rules\ValidateAccountCreditRule;
use App\Rules\ValidateRateChkRule;
use Illuminate\Validation\Rule;

class RateStarsRequest extends Request
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
            'levelID' => Rule::exists(Level::class, 'id'),
            'stars' => 'between:1,10',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'rs' => 'required',
            'chk' => new ValidateRateChkRule()
        ];
    }
}
