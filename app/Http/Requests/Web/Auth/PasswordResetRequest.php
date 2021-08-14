<?php

namespace App\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class PasswordResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['_' => "string", 'password' => "string", 'password_confirmation' => "string"])] public function rules(): array
    {
        return [
            '_' => 'required',
            'password' => 'required',
            'password_confirmation' => 'same:password'
        ];
    }
}
