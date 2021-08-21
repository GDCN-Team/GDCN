<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Rules\ValidateAccountCreditRule;
use App\Rules\ValidateDownloadLevelChkRule;
use Illuminate\Validation\Rule;

class DownloadRequest extends Request
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
            'udid' => 'required_with:gjp',
            'uuid' => 'required_with:udid',
            'levelID' => Rule::exists(Level::class, 'id'),
            'inc' => 'required',
            'extras' => 'required',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'rs' => 'sometimes',
            'chk' => [
                'required_with:rs',
                new ValidateDownloadLevelChkRule()
            ]
        ];
    }
}
