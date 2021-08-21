<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;

class DeleteRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'commentID' => Rule::exists(Comment::class, 'id'),
            'secret' => Rule::in(['Wmfd2893gb7']),
            'cType' => Rule::in(1)
        ];
    }
}
