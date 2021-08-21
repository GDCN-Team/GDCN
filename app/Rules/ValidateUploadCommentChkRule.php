<?php

namespace App\Rules;

use GDCN\Hash\Components\CommentUploadChk;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ValidateUploadCommentChkRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return app(CommentUploadChk::class)->check(
            Request::get('userName'),
            Request::get('cType', 0),
            Request::get('comment'),
            $value,
            Request::get('levelID', 0),
            Request::get('percent', 0)
        );
    }

    public function message(): string
    {
        return ':attribute validate failed.';
    }
}
