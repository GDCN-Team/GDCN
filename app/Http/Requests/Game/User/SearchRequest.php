<?php

namespace App\Http\Requests\Game\User;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SearchRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'str' => "string", 'page' => "string", 'total' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
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
