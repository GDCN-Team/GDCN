<?php

namespace App\Http\Requests\Web\Auth;

use App\Models\Game\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class RegisterApiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['name' => "\Illuminate\Validation\Rules\Unique", 'email' => "\Illuminate\Validation\Rules\Unique", 'password' => "string", 'password_confirmation' => "string"])] public function rules(): array
    {
        return [
            'name' => Rule::unique(Account::class),
            'email' => Rule::unique(Account::class),
            'password' => 'required',
            'password_confirmation' => 'same:password'
        ];
    }
}
