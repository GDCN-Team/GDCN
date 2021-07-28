<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

/**
 * Class DeleteRequest
 * @package App\Http\Requests\Game\Account\Friend\Request
 */
class DeleteRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->validateAccountGJP();
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
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => 'required',
            'targetAccountID' => [
                'exclude_if:targetAccountID,0', // multi
                Rule::exists(Account::class, 'id')
            ],
            'isSender' => 'boolean',
            'secret' => Rule::in('Wmfd2893gb7'),
            'accounts' => 'required_without:targetAccountID'
        ];
    }
}
