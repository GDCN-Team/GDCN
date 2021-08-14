<?php

namespace App\Http\Requests\Web\Tools;

use App\Models\Game\Account\Link;
use App\Models\Game\Level;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LevelTransOutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'server' => 'required',
            'levelID' => Rule::exists(Level::class, 'id'),
            'linkID' => Rule::exists(Link::class, 'id'),
            'password' => 'required',
            'level_name' => 'nullable',
            'level_desc' => 'nullable',
            'level_song_type' => Rule::in(['original', 'audio_track', 'newgrounds']),
            'level_song_id' => 'nullable',
            'level_unlisted' => 'nullable',
            'level_password' => 'nullable'
        ];
    }
}
