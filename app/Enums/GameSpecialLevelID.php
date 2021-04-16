<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameSpecialLevelID
 *
 * @method static static DAILY()
 * @method static static WEEKLY()
 * @package App\Enums
 */
final class GameSpecialLevelID extends Enum
{
    public const DAILY = -1;
    public const WEEKLY = -2;
}
