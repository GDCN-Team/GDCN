<?php

namespace App\Http\Requests\Game\Account\Message;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use Illuminate\Validation\Rule;

class DeleteRequest extends Request
{
    /**
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
            'gjp' => 'required_with:accountID',
            'secret' => Rule::in('Wmfd2893gb7'),
            'isSender' => [
                'sometimes',
                'boolean'
            ],
            'messageID' => [ // single delete
                'sometimes',
                Rule::exists(Message::class, 'id')
            ],
            'messages' => 'required_without:messageID' // multi delete
        ];
    }
}
