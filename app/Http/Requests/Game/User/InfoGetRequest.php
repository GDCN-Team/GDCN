<?php

namespace App\Http\Requests\Game\User;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class InfoGetRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->has(['accountID', 'gjp'])) {
            return $this->validateAccountGJP();
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
                'sometimes',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'uuid' => 'required_without:gjp',
            'udid' => 'required_with:uuid',
            'targetAccountID' => Rule::exists(Account::class, 'id'),
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
