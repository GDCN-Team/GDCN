<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class DailyGetRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "array", 'gjp' => "array", 'secret' => "\Illuminate\Validation\Rules\In", 'weekly' => "string"])] public function rules(): array
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
            'secret' => Rule::in(['Wmfd2893gb7']),
            'weekly' => 'boolean'
        ];
    }
}
