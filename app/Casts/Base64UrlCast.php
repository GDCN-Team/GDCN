<?php

namespace App\Casts;

use Base64Url\Base64Url;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class Base64UrlCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): string
    {
        return Base64Url::decode($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        try {
            Base64Url::decode($value);
            return $value;
        } catch (InvalidArgumentException) {
            return Base64Url::encode($value, true);
        }
    }
}
