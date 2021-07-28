<?php

namespace App\Http\Requests\Game\Account;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class LoginRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->validateAccount();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'udid' => 'required',
            'userName' => Rule::exists(Account::class, 'name'),
            'password' => 'required',
            'sID' => 'nullable',
            'secret' => Rule::in('Wmfv3899gc9')
        ];
    }
}
