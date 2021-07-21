<?php

namespace App\Http\Requests\Game\Item;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class LikeRequest extends Request
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
                'exclude_if:accountID,0',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required',
            'uuid' => 'required_with:udid',
            'itemID' => 'required',
            'like' => [
                'required',
                'boolean'
            ],
            'type' => [
                'required',
                Rule::in([1, 2, 3])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'special' => 'required',
            'rs' => 'required',
            'chk' => 'required'
        ];
    }
}
