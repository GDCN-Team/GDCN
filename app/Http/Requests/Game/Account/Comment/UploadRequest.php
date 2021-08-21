<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Rules\ValidateAccountCreditRule;
use App\Rules\ValidateUploadCommentChkRule;
use Illuminate\Validation\Rule;

class UploadRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'userName' => Rule::exists(Account::class, 'name'),
            'comment' => 'required',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'cType' => Rule::in(1),
            'chk' => new ValidateUploadCommentChkRule()
        ];
    }
}
