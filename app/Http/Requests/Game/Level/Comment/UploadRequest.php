<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
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
            'levelID' => Rule::exists(Level::class, 'id'),
            'percent' => [
                'sometimes',
                'between:0,100'
            ],
            'chk' => new ValidateUploadCommentChkRule()
        ];
    }
}
