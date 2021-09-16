<?php

namespace App\Services\Game;

use App\Models\Game\Level\Rating;

class OptimizeService
{
    public function removeRatingsWithoutLevel()
    {
        return Rating::whereDoesntHave('level')->delete();
    }
}
