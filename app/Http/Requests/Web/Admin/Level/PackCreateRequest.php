<?php

namespace App\Http\Requests\Web\Admin\Level;

use App\Models\Game\Level\Pack;
use App\Rules\ValidateLevelsExistRule;
use App\Rules\ValidateRgbColorRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class PackCreateRequest extends FormRequest
{
    #[ArrayShape(['name' => "\Illuminate\Validation\Rules\Unique", 'levels' => "\App\Rules\ValidateLevelsExistRule", 'stars' => "string", 'coins' => "string", 'difficulty' => "string", 'text_color' => "\App\Rules\ValidateRgbColorRule", 'bar_color' => "array"])] public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique(Pack::class)
            ],
            'levels' => new ValidateLevelsExistRule(),
            'stars' => 'between:1,10',
            'coins' => 'between:0,3',
            'difficulty' => 'between:1,10',
            'text_color' => new ValidateRgbColorRule(),
            'bar_color' => [
                'nullable',
                new ValidateRgbColorRule()
            ]
        ];
    }
}
