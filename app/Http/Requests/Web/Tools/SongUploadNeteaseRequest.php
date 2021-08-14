<?php

namespace App\Http\Requests\Web\Tools;

use App\Models\Game\CustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SongUploadNeteaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['song_id' => "array", 'musicID' => "string"])] public function rules(): array
    {
        return [
            'song_id' => [
                'integer',
                'gte:' . config('game.customSongIdOffset'),
                Rule::unique(CustomSong::class)
            ],
            'musicID' => 'integer'
        ];
    }
}
