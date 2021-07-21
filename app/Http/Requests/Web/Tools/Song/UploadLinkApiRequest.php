<?php

namespace App\Http\Requests\Web\Tools\Song;

use App\Models\Game\CustomSong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadLinkApiRequest extends FormRequest
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
                Rule::unique(CustomSong::class)
            ],
            'name' => 'required',
            'author_name' => 'required',
            'link' => [
                'required',
                'active_url',
                Rule::unique(CustomSong::class, 'download_url')
            ]
        ];
    }
}
