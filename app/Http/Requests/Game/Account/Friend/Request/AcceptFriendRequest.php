<?php

namespace App\Http\Requests\Game\Account\Friend\Request;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use Illuminate\Validation\Rule;

/**
 * Class AcceptFriendRequest
 * @package App\Http\Requests\Game\Account\Friend\Request
 */
class AcceptFriendRequest extends Request
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
            'gjp' => 'required_with:accountID',
            'targetAccountID' => Rule::exists(Account::class, 'id'),
            'requestID' => Rule::exists(FriendRequest::class, 'id'),
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
