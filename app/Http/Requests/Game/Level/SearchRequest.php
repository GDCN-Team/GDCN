<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class SearchRequest extends Request
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
            'type' => 'between:1,16',
            'str' => 'nullable',
            'page' => 'required',
            'total' => 'required',
            'followed' => 'nullable',

            /* Advanced Options */

            'uncompleted' => [
                'nullable',
                'boolean'
            ],
            'onlyCompleted' => [
                'nullable',
                'boolean'
            ],
            'featured' => [
                'nullable',
                'boolean'
            ],
            'original' => [
                'nullable',
                'boolean'
            ],
            'twoPlayer' => [
                'nullable',
                'boolean'
            ],
            'coins' => [
                'nullable',
                'boolean'
            ],
            'epic' => [
                'nullable',
                'boolean'
            ],
            'completedLevels' => 'sometimes',

            /* Filters */

            'len' => 'sometimes',
            'star' => 'sometimes',
            'diff' => 'exclude_if:diff,-',
            'demonFilter' => [
                'sometimes',
                'between:1,5'
            ],
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
