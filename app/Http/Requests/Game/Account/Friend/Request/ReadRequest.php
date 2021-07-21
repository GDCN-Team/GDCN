<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use Illuminate\Validation\Rule;

/**
 * Class ReadRequest
 * @package App\Http\Requests\Game\Account\Friend\Request
 */
class ReadRequest extends Request
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
            'requestID' => Rule::exists(FriendRequest::class, 'id'),
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
