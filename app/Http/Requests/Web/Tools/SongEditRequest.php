<?php

namespace App\Http\Requests\Web\Tools;

use App\Models\Game\CustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SongEditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['id' => "\Illuminate\Validation\Rules\Exists", 'name' => "string", 'author_name' => "string"])] public function rules(): array
    {
        return [
            'id' => Rule::exists(CustomSong::class),
            'name' => 'required',
            'author_name' => 'required'
        ];
    }
}
