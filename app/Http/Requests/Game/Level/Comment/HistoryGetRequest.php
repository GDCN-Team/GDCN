<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\User;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class HistoryGetRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'userID' => "\Illuminate\Validation\Rules\Exists", 'mode' => "\Illuminate\Validation\Rules\In", 'page' => "string", 'total' => "string", 'secret' => "\Illuminate\Validation\Rules\In", 'count' => "string"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'userID' => Rule::exists(User::class, 'id'),
            'mode' => Rule::in([0, 1]),
            'page' => 'integer',
            'total' => 'nullable',
            'secret' => Rule::in(['Wmfd2893gb7']),
            'count' => 'sometimes'
        ];
    }
}
