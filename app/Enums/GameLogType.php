<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameLogType
 *
 * @method static static DOWNLOADED_LEVEL()
 * @method static static LIKE_LEVEL()
 * @method static static LIKE_LEVEL_COMMENT()
 * @method static static LIKE_ACCOUNT_COMMENT()
 * @method static static UPLOAD_SONG()
 * @method static static REUPLOAD_LEVEL()
 * @method static static REPORT_LEVEL()
 * @method static static DO_ACCOUNT_COMMENT_COMMAND()
 * @method static static DO_LEVEL_COMMENT_COMMAND()
 * @package App\Enums
 */
final class GameLogType extends Enum
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
}
