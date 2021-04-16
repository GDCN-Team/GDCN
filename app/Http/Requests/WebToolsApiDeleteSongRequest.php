<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use App\Models\GameCustomSong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WebToolsApiDeleteSongRequest extends ApiRequest
{
    /**
     * @var GameCustomSong
     */
    public $song;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->songID)) {
            return false;
        }

        $this->song = GameCustomSong::whereSongId($this->songID)->firstOrFail();

        /** @var GameAccount $account */
        $account = Auth::user();

        if (!$this->song->owner->is($account)) {
            return false;
        }

        return true;
    }

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
                Rule::exists(GameCustomSong::class, 'song_id')
            ]
        ];
    }
}
