<?php

namespace App\Http\Requests\Game\Account\Setting;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
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
            'accountID' => [
                'required',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'mS' => [
                'required',
                Rule::in([0, 1, 2])
            ],
            'frS' => [
                'required',
                Rule::in([0, 1])
            ],
            'cS' => [
                'required',
                Rule::in([0, 1, 2])
            ],
            'yt' => 'nullable',
            'twitter' => 'nullable',
            'twitch' => 'nullable',
            'secret' => [
                'required',
                Rule::in('Wmfv3899gc9')
            ]
        ];
    }
}
