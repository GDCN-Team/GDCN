<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameAccountMessageGetRequest extends GameRequest
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
            'page' => [
                'required',
                'integer'
            ],
            'total' => 'required_with:page',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'getSent' => [
                'sometimes',
                'required',
                'boolean'
            ]
        ];
    }
}
