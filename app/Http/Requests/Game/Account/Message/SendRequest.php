<?php

namespace App\Http\Requests\Game\Account\Message;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SendRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "\Illuminate\Validation\Rules\Exists", 'gjp' => "\App\Rules\ValidateAccountCreditRule", 'toAccountID' => "\Illuminate\Validation\Rules\Exists", 'subject' => "string", 'body' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'toAccountID' => Rule::exists(Account::class, 'id'),
            'subject' => 'required',
            'body' => 'required',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
