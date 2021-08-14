<?php

namespace App\Http\Requests\Web\Tools;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class LevelTransInRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['server' => "string", 'levelID' => "string"])] public function rules(): array
    {
        return [
            'server' => 'required',
            'levelID' => [
                'integer',
                'gt:0'
            ]
        ];
    }
}
