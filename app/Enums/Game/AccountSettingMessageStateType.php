<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

/**
 * Class AccountSettingMessageStateType
 * @package App\Enums\Game
 */
final class AccountSettingMessageStateType extends Enum
{
    public const ALL = 0;
    public const FRIENDS = 1;
    public const NONE = 2;
}
