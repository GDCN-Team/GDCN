<?php

namespace App\Http\Requests\Game\User;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class SearchRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'str' => 'required',
            'page' => 'integer',
            'total' => 'nullable',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
