<?php

namespace App\Http\Requests\Game\Account;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class LoginRequest extends Request
{
    #[ArrayShape(['userName' => "\Illuminate\Validation\Rules\Exists", 'password' => "\App\Rules\ValidateAccountCreditRule", 'udid' => "string", 'sID' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'userName' => Rule::exists(Account::class, 'name'),
            'password' => new ValidateAccountCreditRule(),
            'udid' => 'required',
            'sID' => 'nullable',
            'secret' => Rule::in(['Wmfv3899gc9'])
        ];
    }
}
