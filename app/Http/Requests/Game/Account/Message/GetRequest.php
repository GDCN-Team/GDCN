<?php

namespace App\Http\Requests\Game\Account\Message;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GetRequest extends Request
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
