<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class PackGetRequest extends Request
{
    public function rules(): array
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
