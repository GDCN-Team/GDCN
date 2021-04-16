<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class GameUserSearchRequest extends GameRequest
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
            'str' => 'required',
            'page' => 'required',
            'total' => 'required_with:page',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
