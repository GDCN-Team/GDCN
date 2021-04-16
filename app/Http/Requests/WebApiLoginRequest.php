<?php

namespace App\Http\Requests;

class WebApiLoginRequest extends ApiRequest
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
            'password' => 'required'
        ];
    }
}
