<?php

namespace App\Http\Requests\Web\Tools;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class AccountLinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['server' => "string", 'name' => "string", 'password' => "string"])] public function rules(): array
    {
        return [
            'server' => 'required',
            'name' => 'required',
            'password' => 'required'
        ];
    }
}
