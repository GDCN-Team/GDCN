<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\User;
use Illuminate\Validation\Rule;

class HistoryGetRequest extends Request
{
    public function rules(): array
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
