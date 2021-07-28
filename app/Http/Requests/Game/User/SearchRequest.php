<?php

namespace App\Http\Requests\Game\User;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class SearchRequest extends Request
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
            'str' => 'required',
            'page' => 'required',
            'total' => 'required_with:page',
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
