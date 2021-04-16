<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountMessageSendRequest extends GameRequest
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
            'gameVersion' => [
                'required',
                'gte:21'
            ],
            'binaryVersion' => 'required_with:gameVersion',
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'toAccountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'subject' => 'required',
            'body' => 'required_with:subject',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
