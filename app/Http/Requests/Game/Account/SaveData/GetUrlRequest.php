<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class GetUrlRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'accountID' => [
                'required',
                Rule::exists(Account::class, 'id')
            ],
            'type' => [
                'required',
                Rule::in([1, 2])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
