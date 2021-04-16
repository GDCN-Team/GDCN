<?php

namespace App\Http\Requests;

class WebApiAccountPasswordUpdateRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required'
        ];
    }
}
