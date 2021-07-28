<?php

namespace App\Http\Requests\Game\User\Score;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class GetRequest extends Request
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
            'udid' => 'required_without:gjp',
            'uuid' => 'required_with:udid',
            'type' => Rule::in(['top', 'friends', 'relative', 'creators']),
            'count' => 'required',
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
