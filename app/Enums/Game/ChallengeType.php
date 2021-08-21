<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

final class ChallengeType extends Enum
{
    const UNKNOWN = 0;
    const ORBS = 1;
    const COINS = 2;
    const STARS = 3;
}
