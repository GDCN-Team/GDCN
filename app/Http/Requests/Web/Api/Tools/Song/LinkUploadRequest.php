<?php

namespace App\Http\Requests\Web\Api\Tools\Song;

use App\Http\Requests\Web\Api\Request;
use App\Models\Game\CustomSong;
use Illuminate\Validation\Rule;

class LinkUploadRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'author_name' => 'required',
            'url' => [
                'required',
                'active_url'
            ],
            'song_id' => [
                'gte:' . config('game.customSongIdOffset'),
                Rule::unique(CustomSong::class)
            ]
        ];
    }
}
