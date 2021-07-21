<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Game\Components\Hash\Checker;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use GDCN\ChkValidationException;
use Illuminate\Validation\Rule;
use function app;

class UploadRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->auth();
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
            'accountID' => [
                'required',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'userName' => 'required',
            'comment' => 'required',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'levelID' => [
                'required',
                Rule::exists(Level::class, 'id')
            ],
            'percent' => [
                'sometimes',
                'between:0,100'
            ],
            'chk' => 'required'
        ];
    }

    /**
     * @throws ChkValidationException
     */
    public function validateChk(): void
    {
        app(Checker::class)->uploadLevelComment($this);
    }
}
