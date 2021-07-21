<?php

namespace App\Services\Game;

use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * Class HelperService
 * @package App\Services\Game
 */
class HelperService
{
    public mixed $perPage;

    public function __construct()
    {
        $this->perPage = Config::get('game.perPage', 10);
    }

    /**
     * @param int $total
     * @param int $page
     * @param int|null $perPage
     * @return string
     */
    public function generatePageHash(int $total, int $page, int $perPage = null): string
    {
        if (!$perPage) {
            $perPage = $this->perPage;
        }

        return $total . ':' . max(0, $perPage * --$page) . ':' . $perPage;
    }

    /**
     * @param Model|int $value
     * @return mixed
     */
    public function getID(Model|int $value): mixed
    {
        return $value instanceof Model ? $value->getKey() : $value;
    }

    /**
     * @param int|Model $value
     * @param string $targetClassName
     * @return mixed
     */
    public function getModel(Model|int $value, string $targetClassName): mixed
    {
        return $value instanceof $targetClassName ? $value : $targetClassName::findOrFail($value);
    }

    /**
     * @return void
     */
    public function setCarbonLocaleToEnglish(): void
    {
        Carbon::setLocale('en');
    }

    /**
     * @param string $comment
     * @param bool $urlBase64Decode
     * @return bool
     */
    public function checkSpam(string $comment, bool $urlBase64Decode = true): bool
    {
        $comment = $urlBase64Decode ? Base64Url::decode($comment) : $comment;
        $spamWords = config('game.spamWords', []);

        foreach ($spamWords as $spamWord) {
            if (stripos($comment, $spamWord)) {
                return true;
            }
        }

        return false;
    }
}
