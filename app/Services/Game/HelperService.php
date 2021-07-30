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
     * @param Model|int|null $value
     * @param string $targetClassName
     * @return mixed
     */
    public function getModel(Model|int|null $value, string $targetClassName): mixed
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

    /**
     * @param int|null $rating
     * @return int
     */
    public function guessDemonDifficultyFromRating(?int $rating): int
    {
        return match ($rating) {
            1 => 3,
            2 => 4,
            4 => 5,
            5 => 6,
            default => 0,
        };
    }

    /**
     * @param $stars
     * @return array
     */
    public function guessDiffFromStars($stars): array
    {
        $auto = false;
        $demon = false;

        switch ($stars) {
            case 1:
                $name = 'Auto';
                $diff = 50;
                $auto = true;
                break;
            case 2:
                $name = 'Easy';
                $diff = 10;
                break;
            case 3:
                $name = 'Normal';
                $diff = 20;
                break;
            case 4:
            case 5:
                $name = 'Hard';
                $diff = 30;
                break;
            case 6:
            case 7:
                $name = 'Harder';
                $diff = 40;
                break;
            case 8:
            case 9:
                $name = 'Insane';
                $diff = 50;
                break;
            case 10:
                $name = 'Demon';
                $diff = 50;
                $demon = true;
                break;
            default:
                $name = $stars > 10 ? 'Demon' : 'N/A';
                $diff = $stars > 10 ? 50 : 0;
                $demon = $stars > 10;
                break;
        }

        return [$name, $diff, $auto, $demon];
    }
}
