<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

final class LogType extends Enum
{
    public const DOWNLOADED_LEVEL = 1;
    public const LIKE_LEVEL = 2;
    public const LIKE_LEVEL_COMMENT = 3;
    public const LIKE_ACCOUNT_COMMENT = 4;
    public const UPLOAD_SONG = 5;
    public const REUPLOAD_LEVEL = 6;
    public const REPORT_LEVEL = 7;
    public const DO_ACCOUNT_COMMENT_COMMAND = 8;
    public const DO_LEVEL_COMMENT_COMMAND = 9;
    public const UPDATE_PROFILE = 10;
}
