<?php

namespace App\Http\Requests\Game\Item;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class RestoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'udid' => 'required',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
