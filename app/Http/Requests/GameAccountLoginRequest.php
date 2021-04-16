<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountLoginRequest extends GameRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            return $this->auth();
        } catch (GameAuthenticationException $e) {
            return false;
        }
    }

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
