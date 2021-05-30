<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebDashboardPasswordChangeApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'new_password' => 'required',
            'password_confirmation' => [
                'required',
                'password'
            ]
        ];
    }
}
