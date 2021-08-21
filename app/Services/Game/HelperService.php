<?php

namespace App\Services\Game;

class HelperService
{
    public function checkSpam(string $comment): bool
    {
        $spamWords = config('game.spamWords', []);

        foreach ($spamWords as $spamWord) {
            if (stripos($comment, $spamWord)) {
                return true;
            }
        }

        return false;
    }
}
