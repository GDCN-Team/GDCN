<?php

namespace App\Enums\Game\Account\Setting;

use BenSampo\Enum\Enum;

/**
 * Class CommentHistoryState
 * @package App\Enums\Game
 */
final class CommentHistoryState extends Enum
{
    public const ALL = 0;
    public const FRIENDS = 1;
    public const NONE = 2;
}
