<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

final class FriendState extends Enum
{
    public const NONE = 0;
    public const IS = 1;
    public const REQUEST_IN_COMING = 3;
    public const REQUEST_OUT_COMING = 4;
}
