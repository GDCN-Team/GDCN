<?php

namespace App\Http\Requests\Game\Account\Message;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class DownloadRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'messageID' => Rule::exists(Message::class, 'id'),
            'secret' => Rule::in(['Wmfd2893gb7']),
            'isSender' => 'sometimes|boolean'
        ];
    }
}
