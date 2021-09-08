<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ValidateRgbColorRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (Str::startsWith($value, 'rgb(')) {
            $value = substr($value, 4, -1);
        }

        $parts = explode(',', $value);
        if (count($parts) !== 3) {
            return false;
        }

        foreach ($parts as $part) {
            if ($part < 0 || $part > 255) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Invalid rgb color.';
    }
}
