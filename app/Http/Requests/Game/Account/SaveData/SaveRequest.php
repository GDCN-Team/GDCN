<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class SaveRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'userName' => Rule::exists(Account::class, 'name'),
            'password' => new ValidateAccountCreditRule(),
            'saveData' => 'required',
            'secret' => Rule::in(['Wmfv3899gc9'])
        ];
    }
}
