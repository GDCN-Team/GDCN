<?php

namespace App\Http\Requests;

use App\Models\GameLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebToolsLevelTransInApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'server' => [
                'required',
                Rule::in([
                    'official'
                ])
            ],
            'levelID' => [
                'required',
                'integer',
                Rule::unique(GameLevel::class, 'original')
            ]
        ];
    }
}
