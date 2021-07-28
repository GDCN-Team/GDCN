<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class HistoryGetRequest extends Request
{
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
            'page' => 'required',
            'total' => 'required_with:page',
            'secret' => Rule::in('Wmfd2893gb7'),
            'mode' => Rule::in([0, 1]),
            'userID' => Rule::exists(User::class, 'id'),
            'count' => 'sometimes'
        ];
    }
}
