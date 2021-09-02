<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

final class BanType extends Enum
{
    const BAN = 0;
    const ACCOUNT_COMMENT_BAN = 1;
    const LEVEL_COMMENT_BAN = 2;
}
