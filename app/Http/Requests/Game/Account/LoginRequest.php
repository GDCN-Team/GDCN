<?php

namespace App\Http\Requests\Game\Account;

use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'udid' => [
                'required',
                'string'
            ],
            'userName' => [
                'required',
                Rule::exists(GameAccount::class, 'name')
            ],
            'password' => 'required',
            'sID' => [
                'sometimes',
                'required'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfv3899gc9')
            ]
        ];
    }
}
