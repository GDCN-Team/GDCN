<?php

namespace App\Http\Requests;

use App\Models\GameCustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebToolsSongUpdateApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                Rule::exists(GameCustomSong::class)
            ],
            'song_id' => [
                'required',
                'integer'
            ],
            'name' => 'required',
            'author_name' => 'required'
        ];
    }
}
