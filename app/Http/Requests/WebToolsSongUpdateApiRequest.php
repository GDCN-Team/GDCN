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
            'song_id' => [
                'required',
                'gte:' . config('game.customSongIdOffset'),
                Rule::unique(GameCustomSong::class)
            ],
            'name' => 'required',
            'author_name' => 'required'
        ];
    }
}
