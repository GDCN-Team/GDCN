<?php

namespace App\Enums\Game\Level\Rating;

use BenSampo\Enum\Enum;

/**
 * Class SuggestionType
 * @package App\Enums\Game
 */
final class SuggestionType extends Enum
{
    public const SUGGEST = 1;
    public const RATE = 2;
    public const DEMON = 3;
}
