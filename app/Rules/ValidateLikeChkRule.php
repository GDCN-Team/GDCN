<?php

namespace App\Rules;

use GDCN\Hash\Components\LikeChk as LikeChkComponent;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ValidateLikeChkRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return app(LikeChkComponent::class)->check(
            Request::get('special'),
            Request::get('itemID'),
            Request::get('like'),
            Request::get('type'),
            Request::get('rs'),
            Request::get('accountID', 0),
            Request::get('udid'),
            Request::get('uuid'),
            $value
        );
    }

    public function message(): string
    {
        return ':attribute validate failed.';
    }
}
