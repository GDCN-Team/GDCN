<?php

namespace App\Http\Requests;

use App\Models\GameCustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebToolsSongUploadLinkApiRequest extends FormRequest
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
                Rule::unique(GameCustomSong::class)
            ],
            'name' => 'required',
            'author_name' => 'required',
            'link' => [
                'required',
                'active_url',
                Rule::unique(GameCustomSong::class, 'download_url')
            ]
        ];
    }
}
