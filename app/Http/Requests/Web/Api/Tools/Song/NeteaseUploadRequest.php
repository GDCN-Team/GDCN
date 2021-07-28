<?php

namespace App\Http\Requests\Web\Api\Tools\Song;

use App\Http\Requests\Web\Api\Request;
use App\Models\Game\CustomSong;
use Illuminate\Validation\Rule;

class NeteaseUploadRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'song_id' => [
                'gte:' . config('game.customSongIdOffset'),
                Rule::unique(CustomSong::class)
            ]
        ];
    }
}
