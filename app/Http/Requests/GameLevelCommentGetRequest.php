<?php

namespace App\Http\Requests;

use App\Models\GameLevel;
use Illuminate\Validation\Rule;

class GameLevelCommentGetRequest extends GameRequest
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
