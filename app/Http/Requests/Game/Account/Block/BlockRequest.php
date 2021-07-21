<?php

namespace App\Http\Requests\Game\Account\Block;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

/**
 * Class GameAccountBlockRequest
 * @package App\Http\Requests
 */
class BlockRequest extends Request
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
            'targetAccountID' => Rule::exists(Account::class, 'id'),
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
