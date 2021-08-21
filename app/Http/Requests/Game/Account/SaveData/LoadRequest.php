<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class LoadRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'userName' => Rule::exists(Account::class, 'name'),
            'password' => new ValidateAccountCreditRule(),
            'secret' => Rule::in(['Wmfv3899gc9'])
        ];
    }
}
