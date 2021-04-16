<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

/**
 * Class GameAccountBlockRequest
 * @package App\Http\Requests
 */
class GameAccountBlockRequest extends GameRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            $this->auth();
        } catch (GameAuthenticationException $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'targetAccountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
