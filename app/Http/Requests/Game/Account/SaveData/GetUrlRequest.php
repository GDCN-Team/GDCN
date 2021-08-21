<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class GetUrlRequest extends Request
{
    public function rules(): array
    {
        return [
            'accountID' => Rule::exists(Account::class, 'id'),
            'type' => Rule::in([1, 2]),
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
