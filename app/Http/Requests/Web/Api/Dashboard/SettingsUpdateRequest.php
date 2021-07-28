<?php

namespace App\Http\Requests\Web\Api\Dashboard;

use App\Http\Requests\Web\Api\Request;
use App\Models\Game\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsUpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $accountID = Auth::id();

        return [
            'name' => Rule::unique(Account::class)->ignore($accountID),
            'password' => 'nullable',
            'email' => Rule::unique(Account::class)->ignore($accountID),
            'password_confirmation' => [
                'required',
                'password'
            ]
        ];
    }
}
