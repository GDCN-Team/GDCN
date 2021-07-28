<?php

namespace App\Http\Requests\Web\Api\Tools\Level;

use App\Http\Requests\Web\Api\Request;
use App\Models\Game\Account\Link;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;

class TransOutRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'link' => Rule::exists(Link::class, 'id'),
            'id' => Rule::exists(Level::class),
            'song_type' => Rule::in(['original', 'audio_track', 'newgrounds']),
            'song' => 'required_unless:song_type,original',
            'password' => 'required'
        ];
    }
}
