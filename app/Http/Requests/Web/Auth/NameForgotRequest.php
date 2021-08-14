<?php

namespace App\Http\Requests\Web\Auth;

use App\Models\Game\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class NameForgotRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['email' => "\Illuminate\Validation\Rules\Exists", 'password' => "string"])] public function rules(): array
    {
        return [
            'email' => Rule::exists(Account::class),
            'password' => 'present'
        ];
    }
}
