<?php

namespace App\Http\Requests;

use App\Models\GameLevel;
use Illuminate\Validation\Rule;

class GameLevelReportRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'levelID' => [
                'required',
                Rule::exists(GameLevel::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
