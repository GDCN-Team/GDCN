<?php

namespace App\Http\Requests\Game\Song;

use App\Http\Requests\Game\Request;
use Illuminate\Validation\Rule;

class GetRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'songID' => 'required',
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
