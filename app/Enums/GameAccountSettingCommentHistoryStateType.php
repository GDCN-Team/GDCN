<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameAccountSettingCommentHistoryStateType
 * @package App\Enums
 */
final class GameAccountSettingCommentHistoryStateType extends Enum
{
    public const ALL = 0;
    public const FRIENDS = 1;
    public const NONE = 2;
}
