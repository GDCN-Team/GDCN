<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;

class UploadRequest extends Request
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
            'userName' => 'required',
            'comment' => 'required',
            'secret' => Rule::in('Wmfd2893gb7'),
            'levelID' => Rule::exists(Level::class, 'id'),
            'percent' => [
                'sometimes',
                'between:0,100'
            ],
            'chk' => 'required'
        ];
    }
}
