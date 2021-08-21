<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class ReadRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'requestID' => Rule::exists(FriendRequest::class, 'id'),
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
