<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class ReadRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "\Illuminate\Validation\Rules\Exists", 'gjp' => "\App\Rules\ValidateAccountCreditRule", 'requestID' => "\Illuminate\Validation\Rules\Exists", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
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
