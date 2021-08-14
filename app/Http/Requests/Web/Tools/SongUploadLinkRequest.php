<?php

namespace App\Http\Requests\Web\Tools;

use App\Models\Game\CustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SongUploadLinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['song_id' => "\Illuminate\Validation\Rules\Unique", 'name' => "string", 'author_name' => "string", 'link' => "string"])] public function rules(): array
    {
        return [
            'song_id' => [
                'integer',
                'gte:' . config('game.customSongIdOffset'),
                Rule::unique(CustomSong::class)
            ],
            'name' => 'required',
            'author_name' => 'required',
            'link' => 'active_url'
        ];
    }
}
