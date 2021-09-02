<?php

namespace App\Rules;

use GDCN\Hash\Components\UpdateUserScoreSeed2 as UpdateUserScoreSeed2Component;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ValidateUpdateUserScoreSeed2Rule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return true;

        /* TODO: fix
         return app(UpdateUserScoreSeed2Component::class)->check(
            Request::get('accountID', 0),
            Request::get('userCoins'),
            Request::get('demons'),
            Request::get('stars'),
            Request::get('coins'),
            Request::get('iconType'),
            Request::get('icon'),
            Request::get('diamonds'),
            Request::get('accIcon'),
            Request::get('accShip'),
            Request::get('accBall'),
            Request::get('accBird'),
            Request::get('accDart'),
            Request::get('accRobot'),
            Request::get('accGlow'),
            Request::get('accSpider'),
            Request::get('accExplosion'),
            $value
        );
         */
    }

    public function message(): string
    {
        return ':attribute validate failed.';
    }
}
