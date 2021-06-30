<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\GameLevel;
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
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'page' => 'required',
            'total' => 'required_with:page',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'mode' => [
                'required',
                Rule::in([0, 1])
            ],
            'levelID' => [
                'required',
                Rule::exists(GameLevel::class, 'id')
            ]
        ];
    }
}
