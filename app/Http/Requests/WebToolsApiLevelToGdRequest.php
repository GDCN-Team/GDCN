<?php

namespace App\Http\Requests;

use App\Models\GameAccountLink;
use Illuminate\Validation\Rule;

class WebToolsApiLevelToGdRequest extends ApiRequest
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
            'custom_server_url' => 'required_if:server,custom',
            'linkID' => Rule::exists(GameAccountLink::class, 'id'),
            'password' => 'required',
            'songType' => [
                'required',
                Rule::in(['original', 'official', 'newgrounds'])
            ],
            'songID' => 'required',
            'levelID' => 'required'
        ];
    }
}
