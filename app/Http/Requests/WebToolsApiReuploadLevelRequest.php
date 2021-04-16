<?php

namespace App\Http\Requests;

class WebToolsApiReuploadLevelRequest extends ApiRequest
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
            'levelID' => 'required'
        ];
    }
}
