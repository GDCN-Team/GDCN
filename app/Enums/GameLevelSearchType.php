<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class GameLevelSearchType
 *
 * @method static static SEARCH()
 * @method static static MOST_DOWNLOADED()
 * @method static static MOST_LIKED()
 * @method static static TRENDING()
 * @method static static RECENT()
 * @method static static USER()
 * @method static static FEATURED()
 * @method static static MAGIC()
 * @method static static PACK()
 * @method static static AWARDED()
 * @method static static FOLLOWED()
 * @method static static FRIENDS()
 * @method static static HALL_OF_FAME()
 * @package App\Enums
 */
final class GameLevelSearchType extends Enum
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
