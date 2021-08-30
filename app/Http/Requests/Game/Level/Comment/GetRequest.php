<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GetRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'mode' => "\Illuminate\Validation\Rules\In", 'page' => "string", 'total' => "string", 'secret' => "\Illuminate\Validation\Rules\In", 'levelID' => "\Illuminate\Validation\Rules\Exists"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'mode' => Rule::in([0, 1]),
            'page' => 'integer',
            'total' => 'nullable',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'levelID' => Rule::exists(Level::class, 'id')
        ];
    }
}
