<?php

namespace App\Http\Requests\Game\Account;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class RegisterRequest extends Request
{
    #[ArrayShape(['userName' => "\Illuminate\Validation\Rules\Unique", 'password' => "string", 'email' => "\Illuminate\Validation\Rules\Unique", 'sID' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'userName' => Rule::unique(Account::class, 'name'),
            'password' => 'required',
            'email' => Rule::unique(Account::class),
            'sID' => 'nullable',
            'secret' => Rule::in(['Wmfv3899gc9'])
        ];
    }
}
