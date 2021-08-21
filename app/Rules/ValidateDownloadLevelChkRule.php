<?php

namespace App\Rules;

use GDCN\Hash\Components\DownloadLevelChk as DownloadLevelChkComponent;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ValidateDownloadLevelChkRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return app(DownloadLevelChkComponent::class)->check(
            Request::get('levelID'),
            Request::get('inc'),
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
