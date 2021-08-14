<?php

namespace App\Http\Requests\Web\Dashboard\Setting;

use App\Models\Game\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['name' => "\Illuminate\Validation\Rules\Unique", 'email' => "\Illuminate\Validation\Rules\Unique", 'password' => "\Illuminate\Validation\Rules\Unique"])] public function rules(): array
    {
        $accountID = Auth::id();

        return [
            'name' => Rule::unique(Account::class)->ignore($accountID),
            'email' => Rule::unique(Account::class)->ignore($accountID),
            'password' => Rule::unique(Account::class)->ignore($accountID)
        ];
    }
}
