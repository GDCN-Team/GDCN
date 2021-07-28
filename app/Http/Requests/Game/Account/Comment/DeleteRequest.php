<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use Illuminate\Validation\Rule;

/**
 * Class DeleteRequest
 * @package App\Http\Requests
 */
class DeleteRequest extends Request
{
    /**
     * @inerhitDoc
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
            'gjp' => 'required',
            'commentID' => Rule::exists(Comment::class, 'id'),
            'secret' => Rule::in('Wmfd2893gb7'),
            'cType' => Rule::in(1)
        ];
    }
}
