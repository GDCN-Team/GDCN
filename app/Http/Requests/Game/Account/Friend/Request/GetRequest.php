<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GetRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "\Illuminate\Validation\Rules\Exists", 'gjp' => "\App\Rules\ValidateAccountCreditRule", 'page' => "string", 'secret' => "\Illuminate\Validation\Rules\In", 'getSent' => "string"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'page' => 'integer',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'getSent' => 'boolean'
        ];
    }
}
