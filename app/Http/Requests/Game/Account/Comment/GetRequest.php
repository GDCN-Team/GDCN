<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GetRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "\Illuminate\Validation\Rules\Exists", 'page' => "string", 'total' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'page' => 'integer',
            'total' => 'nullable',
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
