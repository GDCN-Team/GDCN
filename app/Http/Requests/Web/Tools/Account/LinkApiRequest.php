<?php

namespace App\Http\Requests\Web\Tools\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkApiRequest extends FormRequest
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
            'target_name' => 'required',
            'target_password' => 'required'
        ];
    }
}
