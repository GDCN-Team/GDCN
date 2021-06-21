<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

/**
 * Class AccountSettingCommentHistoryStateType
 * @package App\Enums\Game
 */
final class AccountSettingCommentHistoryStateType extends Enum
{
    public const ALL = 0;
    public const FRIENDS = 1;
    public const NONE = 2;
}
