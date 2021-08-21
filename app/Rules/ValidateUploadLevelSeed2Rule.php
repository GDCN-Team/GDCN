<?php

namespace App\Rules;

use GDCN\Hash\Components\LevelString as LevelStringComponent;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ValidateUploadLevelSeed2Rule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return app(LevelStringComponent::class)->checkUploadLevelSeed2(
            Request::get('levelString'),
            $value
        );
    }

    public function message(): string
    {
        return ':attribute validate failed.';
    }
}
