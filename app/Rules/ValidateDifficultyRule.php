<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateDifficultyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return in_array($value, [10, 20, 30, 40, 50, 60]);
    }

    public function message(): string
    {
        return 'Difficulty is invalid.';
    }
}
