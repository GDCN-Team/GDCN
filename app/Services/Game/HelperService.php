<?php

namespace App\Services\Game;

use App\Models\Game\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class HelperService
{
    public function checkSpam(string $comment): bool
    {
        $spamWords = config('game.spamWords', []);

        Log::channel('gdcn')
            ->info('[Helper] Action: Check Spam', [
                'comment' => $comment,
                'spamWords' => $spamWords
            ]);

        foreach ($spamWords as $spamWord) {
            if (stripos($comment, $spamWord)) {
                return true;
            }
        }

        return false;
    }

    public function resolveUser(string $uuid, string $name = null, string $udid = null): User
    {
        Log::channel('gdcn')
            ->info('[Helper] Action: Resolve User', [
                'uuid' => str_repeat('*', strlen($uuid)),
                'name' => $name,
                'udid' => str_repeat('*', strlen($udid))
            ]);

        return User::firstOrCreate([
            'uuid' => $uuid
        ], [
            'name' => Request::get('userName', $name ?? 'Player'),
            'uuid' => $uuid,
            'udid' => Request::get('udid', $udid)
        ]);
    }
}
