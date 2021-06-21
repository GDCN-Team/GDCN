<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountLoginRequest extends GameRequest
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
