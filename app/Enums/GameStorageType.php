<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameStorageType
 *
 * @method static static SAVE_DATA()
 * @method static static LEVELS()
 * @package App\Enums
 */
final class GameStorageType extends Enum
{
    public const SAVE_DATA = 1;
    public const LEVELS = 2;
}
