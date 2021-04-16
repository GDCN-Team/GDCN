<?php

namespace App\Http\Requests;

use App\Models\GameCustomSong;
use Illuminate\Validation\Rule;

class WebToolsApiUploadSongNeteaseRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'songID' => [
                'required',
                'gte:' . config('game.customSongIdOffset'),
                Rule::unique(GameCustomSong::class, 'song_id')
            ],
            'musicID' => [
                'required',
                'numeric'
            ]
        ];
    }
}
