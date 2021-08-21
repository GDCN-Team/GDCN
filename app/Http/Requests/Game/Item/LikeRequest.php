<?php

namespace App\Http\Requests\Game\Item;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use App\Rules\ValidateLikeChkRule;
use Illuminate\Validation\Rule;

class LikeRequest extends Request
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
            'itemID' => 'required',
            'like' => 'boolean',
            'type' => Rule::in([1, 2, 3]),
            'secret' => Rule::in(['Wmfd2893gb7']),
            'special' => 'required',
            'rs' => 'required',
            'chk' => new ValidateLikeChkRule()
        ];
    }
}
