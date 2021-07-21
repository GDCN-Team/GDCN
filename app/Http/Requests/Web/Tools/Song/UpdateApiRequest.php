<?php

namespace App\Http\Requests\Web\Tools\Song;

use App\Models\Game\CustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function config;

class UpdateApiRequest extends FormRequest
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
                Rule::unique(CustomSong::class)
            ],
            'name' => 'required',
            'author_name' => 'required'
        ];
    }
}
