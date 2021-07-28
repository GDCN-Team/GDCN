<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class GauntletGetRequest extends Request
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
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
