<?php

namespace App\Http\Requests\Game\User;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class ListGetRequest extends Request
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
        } catch (AuthenticationException $e) {
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
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'required',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'type' => [
                'required',
                Rule::in([0, 1])
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
