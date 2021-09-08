<?php

namespace App\Rules;

use App\Models\Game\Level;
use Illuminate\Contracts\Validation\Rule;

class ValidateLevelsExistRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_array($value)) {
            $value = explode(',', $value);
        }

        foreach ($value as $level) {
            if (!Level::find($level)) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Level Not Found.';
    }
}
