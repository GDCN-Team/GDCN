<?php

namespace App\Http\Requests\Game\Item;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class RestoreRequest extends Request
{
    #[ArrayShape(['udid' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'udid' => 'required',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
