<?php

namespace App\Http\Requests\Web\Api\Tools\Level;

use App\Http\Requests\Web\Api\Request;
use App\Rules\ServerRule;
use Illuminate\Foundation\Http\FormRequest;

class TransInRequest extends Request
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
                new ServerRule
            ],
            'id' => [
                'required',
                'integer'
            ]
        ];
    }
}
