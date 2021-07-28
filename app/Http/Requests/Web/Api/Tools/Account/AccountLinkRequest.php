<?php

namespace App\Http\Requests\Web\Api\Tools\Account;

use App\Http\Requests\Web\Api\Request;
use App\Rules\ServerRule;

class AccountLinkRequest extends Request
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
            'name' => 'required',
            'password' => 'required'
        ];
    }
}
