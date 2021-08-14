<?php

namespace App\Http\Requests\Web\Auth;

use App\Models\Game\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class PasswordForgotRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['name' => "\Illuminate\Validation\Rules\Exists", 'email' => "\Illuminate\Validation\Rules\Exists"])] public function rules(): array
    {
        return [
            'name' => Rule::exists(Account::class),
            'email' => Rule::exists(Account::class),
        ];
    }
}
