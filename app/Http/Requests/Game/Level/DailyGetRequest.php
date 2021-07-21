<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class DailyGetRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'sometimes',
                'required',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'weekly' => [
                'required',
                'boolean'
            ]
        ];
    }
}
