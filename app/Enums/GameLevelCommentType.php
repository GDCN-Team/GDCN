<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameLevelCommentType
 *
 * @method static static RECENT()
 * @method static static MOST_LIKED()
 * @package App\Enums
 */
final class GameLevelCommentType extends Enum
{
    public const RECENT = 0;
    public const MOST_LIKED = 1;
}
