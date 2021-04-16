<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameUserListType
 *
 * @method static static FRIENDS()
 * @method static static BLOCKS()
 * @package App\Enums
 */
final class GameUserListType extends Enum
{
    public const FRIENDS = 0;
    public const BLOCKS = 1;
}
