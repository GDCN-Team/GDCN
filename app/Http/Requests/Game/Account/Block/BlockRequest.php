<?php

namespace App\Http\Requests\Game\Account\Block;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

/**
 * Class GameAccountBlockRequest
 * @package App\Http\Requests
 */
class BlockRequest extends Request
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
        } catch (AuthenticationException $e) {
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
            'gameVersion' => [
                'sometimes',
                'required'
            ],
            'binaryVersion' => [
                'sometimes',
                'required'
            ],
            'gdw' => [
                'sometimes',
                'required'
            ],
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
