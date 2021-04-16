<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameLevelSearchRequest extends GameRequest
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
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'type' => [
                'required',
                Rule::in([0, 1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13, 16])
            ],
            'str' => 'nullable',
            'diff' => [
                'exclude_if:diff,-',
                'required'
            ],
            'len' => 'nullable',
            'page' => 'required',
            'total' => 'required',

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
            'completedLevels' => [
                'sometimes',
                'required'
            ],

            /* Filters */

            'demonFilter' => [
                'sometimes',
                'required',
                Rule::in([1, 2, 3, 4, 5])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
