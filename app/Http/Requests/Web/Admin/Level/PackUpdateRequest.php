<?php

namespace App\Http\Requests\Web\Admin\Level;

use App\Models\Game\Level\Pack;
use App\Rules\ValidateLevelsExistRule;
use App\Rules\ValidateRgbColorRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;

class PackUpdateRequest extends FormRequest
{
    #[\JetBrains\PhpStorm\ArrayShape(['name' => "array", 'levels' => "\App\Rules\ValidateLevelsExistRule", 'stars' => "string", 'coins' => "string", 'difficulty' => "string", 'text_color' => "\App\Rules\ValidateRgbColorRule", 'bar_color' => "array"])] public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique(Pack::class)->ignore(Request::route('pack'))
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
