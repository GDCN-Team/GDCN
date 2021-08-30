<?php

namespace App\Http\Requests\Game\Song;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GetRequest extends Request
{
    #[ArrayShape(['songID' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'songID' => 'required',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
