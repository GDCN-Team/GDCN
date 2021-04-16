<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameLikeType
 *
 * @method static static LEVEL()
 * @method static static LEVEL_COMMENT()
 * @method static static ACCOUNT_COMMENT()
 * @package App\Enums
 */
final class GameLikeType extends Enum
{
    public const LEVEL = 1;
    public const LEVEL_COMMENT = 2;
    public const ACCOUNT_COMMENT = 3;
}
