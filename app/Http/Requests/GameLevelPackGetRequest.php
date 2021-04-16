<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class GameLevelPackGetRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'gte:21'
            ],
            'binaryVersion' => 'required_with:gameVersion',
            'gdw' => [
                'required',
                'boolean'
            ],
            'page' => 'required',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
