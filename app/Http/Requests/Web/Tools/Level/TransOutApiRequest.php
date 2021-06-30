<?php

namespace App\Http\Requests\Web\Tools\Level;

use App\Models\GameLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransOutApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'server' => [
                'required',
                Rule::in(['official'])
            ],
            'levelID' => [
                'required',
                'integer',
                Rule::unique(GameLevel::class, 'original')
            ],
            'songType' => [
                'required',
                Rule::in(['original', 'audioTrack', 'customSong'])
            ],
            'songID' => [
                'exclude_if:songType,original',
                'required',
                'integer'
            ],
            'password' => 'required'
        ];
    }
}
