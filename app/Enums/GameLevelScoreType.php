<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameLevelScoreType
 *
 * @method static static FRIENDS()
 * @method static static TOP()
 * @method static static WEEK()
 * @package App\Enums
 */
final class GameLevelScoreType extends Enum
{
    public const FRIENDS = 0;
    public const TOP = 1;
    public const WEEK = 2;
}
