<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GetUrlRequest extends Request
{
    #[ArrayShape(['accountID' => "\Illuminate\Validation\Rules\Exists", 'type' => "\Illuminate\Validation\Rules\In", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'accountID' => Rule::exists(Account::class, 'id'),
            'type' => Rule::in([1, 2]),
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
