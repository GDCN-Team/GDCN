<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class DeleteRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "\Illuminate\Validation\Rules\Exists", 'gjp' => "\App\Rules\ValidateAccountCreditRule", 'commentID' => "\Illuminate\Validation\Rules\Exists", 'secret' => "\Illuminate\Validation\Rules\In", 'cType' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
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
