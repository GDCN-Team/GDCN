<?php

namespace App\Http\Requests\Game\User\Score;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class GetRequest extends Request
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
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_without:accountID,gjp',
            'uuid' => 'required_with:udid',
            'type' => [
                'required',
                Rule::in(['top', 'friends', 'relative', 'creators'])
            ],
            'count' => 'required',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
