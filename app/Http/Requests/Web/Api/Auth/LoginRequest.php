<?php

namespace App\Http\Requests\Web\Api\Auth;

use App\Http\Requests\Web\Api\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package App\Http\Requests\Web\Api\Auth
 */
class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => Rule::exists(Account::class),
            'password' => 'required'
        ];
    }
}
