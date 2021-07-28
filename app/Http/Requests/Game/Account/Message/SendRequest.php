<?php

namespace App\Http\Requests\Game\Account\Message;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class SendRequest extends Request
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
            'toAccountID' => Rule::exists(Account::class, 'id'),
            'subject' => 'required',
            'body' => 'required',
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
