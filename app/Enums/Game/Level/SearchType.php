<?php

namespace App\Enums\Game\Level;

use BenSampo\Enum\Enum;

final class SearchType extends Enum
{
    public const SEARCH = 0;
    public const MOST_DOWNLOADED = 1;
    public const MOST_LIKED = 2;
    public const TRENDING = 3;
    public const RECENT = 4;
    public const USER = 5;
    public const FEATURED = 6;
    public const MAGIC = 7;
    public const PACK = 10;
    public const AWARDED = 11;
    public const FOLLOWED = 12;
    public const FRIENDS = 13;
    public const HALL_OF_FAME = 16;
}
