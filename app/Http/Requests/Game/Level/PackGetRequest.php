<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class PackGetRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'page' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'page' => 'integer',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
