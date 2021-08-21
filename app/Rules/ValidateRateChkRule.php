<?php

namespace App\Rules;

use GDCN\Hash\Components\RateChk as RateChkComponent;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ValidateRateChkRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return app(RateChkComponent::class)->check(
            Request::get('levelID'),
            Request::get('stars'),
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
