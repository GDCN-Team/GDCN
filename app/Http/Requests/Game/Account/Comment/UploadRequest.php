<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use GDCN\ChkValidationException;
use Illuminate\Validation\Rule;

/**
 * Class UploadRequest
 * @package App\Http\Requests
 */
class UploadRequest extends Request
{
    public function authorize()
    {
        $this->validateAccountGJP();
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
            'gjp' => 'required_with:accountID',
            'userName' => 'required',
            'comment' => 'required',
            'secret' => Rule::in('Wmfd2893gb7'),
            'cType' => Rule::in(1),
            'chk' => 'required'
        ];
    }
}
