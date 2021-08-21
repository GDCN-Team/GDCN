<?php

namespace App\Http\Requests\Game\Item;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class RestoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'udid' => 'required',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
