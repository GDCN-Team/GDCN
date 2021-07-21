<?php

namespace App\Http\Requests\Game\Reward;

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
                'required',
                'exclude_if:accountID,0',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable'
            ],
            'udid' => 'required',
            'uuid' => 'required_with:udid',
            'rewardType' => [
                'required',
                Rule::in([0, 1, 2])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'chk' => 'required',
            'r1' => 'required',
            'r2' => 'required'
        ];
    }
}
