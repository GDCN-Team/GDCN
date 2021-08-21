<?php

namespace App\Services\Game;

use App\Models\Game\User;
use Illuminate\Support\Facades\Request;

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

    public function resolveUser(string $uuid, string $name = null, string $udid = null): User
    {
        return User::firstOrCreate([
            'uuid' => $uuid
        ], [
            'name' => Request::get('userName', $name ?? 'Player'),
            'uuid' => $uuid,
            'udid' => Request::get('udid', $udid)
        ]);
    }
}
