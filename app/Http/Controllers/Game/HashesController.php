<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\ChkValidateException;
use App\Http\Controllers\Controller;
use App\Models\GameAccount;
use App\Models\GameAccountComment;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use App\Models\GameUser;
use Base64Url\Base64Url;
use GDCN\XORCipher;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * Class HashesController
 * @package App\Http\Controllers
 */
class HashesController extends Controller
{
    protected const SHA1 = 1;
    protected const XOR = 2;
    protected const BASE64 = 3;

    /**
     * @var int[]
     */
    public $keys = [
        'message' => 14251,
        'level_password' => 26364,
        'account_password' => 37526,
        'level_score' => 39673,
        'level_seed' => 41274,
        'comment' => 29481,
        'challenge' => 19847,
        'reward' => 59182,
        'like' => 58281,
        'rate' => 58281,
        'user' => 85271
    ];

    /**
     * @var string[]
     */
    protected $salts = [
        'level' => 'xI25fpAapCQg',
        'comment' => 'xPT6iUrtws0J',
        'like' => 'ysg6pUrtjn0J',
        'rate' => 'ysg6pUrtjn0J',
        'user' => 'xI35fsAapCRg',
        'level_score' => 'yPg6pUrtWn0J',
        'challenge' => 'oC36fpYaPtdg',
        'reward' => 'pC26fpYaQCtg'
    ];

    /**
     * @param $levelString
     * @return string
     */
    public function generateUploadLevelSeed($levelString): string
    {
        $hash = null;
        $len = strlen($levelString);
        $divided = (int)($len / 50);

        $p = 0;
        for ($k = 0; $k < $len; $k += $divided) {
            if ($p > 49) {
                break;
            }

            $hash .= $levelString[$k];
            $p++;
        }

        $salt = $this->salts['level'];
        return sha1("{$hash}{$salt}");
    }

    /**
     * @param $levelID
     * @param $inc
     * @param $rs
     * @param $accountID
     * @param $udid
     * @param $uuid
     * @return false|string
     */
    public function generateDownloadLevelChk($levelID, $inc, $rs, $accountID, $udid, $uuid)
    {
        return $this->hashChk("{$levelID}{$inc}{$rs}{$accountID}{$udid}{$uuid}{$this->salts['level']}", self::SHA1);
    }

    /**
     * @param $chk
     * @param int $mode
     * @param array $options
     * @return false|string
     */
    protected function hashChk($chk, int $mode, $options = [])
    {
        switch ($mode) {
            case self::SHA1:
                return sha1($chk);
            case self:: XOR:
                return XORCipher::cipher($chk, $options['key'] ?? 0);
            case self::BASE64:
                return Base64Url::encode($chk, $options['up'] ?? true);
            case self:: XOR + self::BASE64:
                return Base64Url::encode(
                    XORCipher::cipher($chk, $options['key'] ?? 0),
                    $options['up'] ?? true
                );
            default:
                return false;
        }
    }

    /**
     * @param $accountID
     * @param $userCoins
     * @param $demons
     * @param $stars
     * @param $coins
     * @param $iconType
     * @param $icon
     * @param $diamonds
     * @param $accIcon
     * @param $accShip
     * @param $accBall
     * @param $accBird
     * @param $accDart
     * @param $accRobot
     * @param $accGlow
     * @param $accSpider
     * @param $accExplosion
     * @return false|string
     */
    public function generateUploadUserScoreChk($accountID, $userCoins, $demons, $stars, $coins, $iconType, $icon, $diamonds, $accIcon, $accShip, $accBall, $accBird, $accDart, $accRobot, $accGlow, $accSpider, $accExplosion)
    {
        return $this->hashChk("{$accountID}{$userCoins}{$demons}{$stars}{$coins}{$iconType}{$icon}{$diamonds}{$accIcon}{$accShip}{$accBall}{$accBird}{$accDart}{$accRobot}{$accGlow}{$accSpider}{$accExplosion}{$this->salts['user']}", self::SHA1);
    }

    /**
     * @param $challenge
     * @return false|string
     */
    public function generateChallengeHash($challenge)
    {
        return $this->hashChk("{$challenge}{$this->salts['challenge']}", self::SHA1);
    }

    /**
     * @param $levelString
     * @return string
     */
    public function generateLevelStringHash($levelString): string
    {
        $hash = 'aaaaa';
        $len = strlen($levelString);
        $divided = (int)($len / 40);

        $p = 0;
        for ($k = 0; $k < $len; $k += $divided) {
            if ($p > 39) {
                break;
            }

            $hash[$p] = $levelString[$k];
            $p++;
        }

        $salt = $this->salts['level'];
        return sha1("{$hash}{$salt}");
    }

    /**
     * @param $userName
     * @param $comment
     * @param bool $xor
     * @return false|string
     */
    public function generateUploadAccountCommentChk($userName, $comment, $xor = false)
    {
        $salt = $this->salts['comment'];
        $chk = $this->hashChk("{$userName}{$comment}001{$salt}", self::SHA1);

        if ($xor) {
            $chk = $this->encodeChk($chk, $this->keys['comment']);
        }

        return $chk;
    }

    /**
     * @param $chk
     * @param $key
     * @param bool $base64
     * @return string
     */
    public function encodeChk($chk, $key, bool $base64 = true): string
    {
        $chk = XORCipher::cipher($chk, $key);
        if ($base64) {
            $chk = Base64Url::encode($chk, true);
        }

        return $chk;
    }

    public function generateUploadLevelCommentChk($userName, $comment, $levelID, $percent = 0, $xor = false)
    {
        $salt = $this->salts['comment'];
        $chk = $this->hashChk("{$userName}{$comment}{$levelID}{$percent}0{$salt}", self::SHA1);

        if ($xor) {
            $chk = $this->encodeChk($chk, $this->keys['comment']);
        }

        return $chk;
    }

    /**
     * @param $gauntlets
     * @return false|string
     */
    public function generateLevelGauntletHash($gauntlets)
    {
        $hash = null;
        foreach ($gauntlets as $gauntlet) {
            $hash .= "{$gauntlet->id}{$gauntlet->levelIds}";
        }

        $salt = $this->salts['level'];
        return $this->hashChk("{$hash}{$salt}", self::SHA1);
    }

    public function generateLevelPackHash($packs)
    {
        $hash = null;
        foreach ($packs as $pack) {
            $id = (string)$pack->id;
            $hash .= "{$id[0]}{$id[-1]}{$pack->stars}{$pack->coins}";
        }

        return $this->hashChk("{$hash}{$this->salts['level']}", self::SHA1);
    }

    /**
     * @param int|GameUser $user
     * @param int $stars
     * @param bool $demon
     * @param int|GameLevel $level
     * @param bool $coinVerified
     * @param int $featuredScore
     * @param int $password
     * @param int|null $feaID
     * @return false|string
     */
    public function generateLevelHash($user, int $stars, bool $demon, $level, bool $coinVerified, int $featuredScore, int $password, ?int $feaID = null)
    {
        $userID = $user->id ?? $user;
        $levelID = $level->id ?? $level;

        return $this->hashChk("{$userID},{$stars},{$this->bool2int($demon)},{$levelID},{$this->bool2int($coinVerified)},{$featuredScore},{$password},{$feaID}{$this->salts['level']}", self::SHA1);
    }

    /**
     * @param bool $flag
     * @return int
     */
    protected function bool2int(bool $flag): int
    {
        return $flag ? 1 : 0;
    }

    /**
     * @param mixed $special
     * @param int|GameLevel|GameLevelComment|GameAccountComment $item
     * @param bool $like
     * @param int $type
     * @param string $rs
     * @param int|GameAccount $account
     * @param string $udid
     * @param string $uuid
     * @return false|string
     */
    public function generateLikeChk($special, $item, bool $like, int $type, string $rs, $account, string $udid, string $uuid)
    {
        $itemID = $item->id ?? $item;
        $accountID = $account->id ?? $account;

        return $this->hashChk("{$special}{$itemID}{$this->bool2int($like)}{$type}{$rs}{$accountID}{$udid}{$uuid}{$this->salts['like']}", self::SHA1);
    }

    /**
     * @param GameLevel[]|Collection $levels
     * @return false|string
     */
    public function generateLevelListHash($levels)
    {
        $hash = null;
        foreach ($levels as $level) {
            $levelID = (string)$level->id;
            $coinVerified = $level->rating->coin_verified ?? 0;
            $stars = $level->rating->stars ?? 0;

            $hash .= "{$levelID[0]}{$levelID[-1]}{$stars}{$coinVerified}";
        }

        return $this->hashChk("{$hash}{$this->salts['level']}", self::SHA1);
    }

    /**
     * @param string $reward
     * @return false|string
     */
    public function generateRewardHash(string $reward)
    {
        return $this->hashChk("{$reward}{$this->salts['reward']}", self::SHA1);
    }

    /**
     * @param $known_string
     * @param $user_string
     * @throws ChkValidateException
     */
    public function checkChk($known_string, $user_string): void
    {
        if (!hash_equals($known_string, $user_string)) {
            throw new ChkValidateException($known_string . ' not equal as ' . $user_string);
        }
    }

    /**
     * I give up...
     *
     * @param $accountID
     * @param $levelID
     * @param $percent
     * @param int $seconds
     * @param int $jumps
     * @param $attempts
     * @param $seed
     * @param string $bestDifferences
     * @param int $coins
     * @param $timelyID
     * @param $rs
     * @param bool $xor
     * @return false|string
     */
    public function generateUploadLevelScoreChk($accountID, $levelID, $percent, int $seconds, int $jumps, $attempts, $seed, string $bestDifferences, int $coins, $timelyID, $rs, bool $xor = false)
    {
        $seconds -= 4085;
        $jumps -= 3991;
        $bestDifferences = $this->decodeChk($bestDifferences, $this->keys['level_seed']);
        $coins -= 5819;

        $chk = $this->hashChk("{$accountID}{$levelID}{$percent}{$seconds}{$jumps}{$attempts}{$seed}{$bestDifferences}1{$coins}{$timelyID}{$rs}{$this->salts['level_score']}", self::SHA1);
        return $xor ? $this->encodeChk($chk, $this->keys['level_score']) : $chk;
    }

    /**
     * @param $levelID
     * @param $stars
     * @param $rs
     * @param $accountID
     * @param $udid
     * @param $uuid
     * @return false|string
     */
    public function generateRateChk($levelID, $stars, $rs, $accountID, $udid, $uuid)
    {
        return $this->hashChk("{$levelID}{$stars}{$rs}{$accountID}{$udid}{$uuid}{$this->salts['rate']}", self::SHA1);
    }

    /**
     * @param $chk
     * @param $key
     * @param bool $base64
     * @return string
     */
    public function decodeChk($chk, $key, bool $base64 = true): string
    {
        try {
            if ($base64) {
                $chk = Base64Url::decode($chk);
            }
        } catch (InvalidArgumentException $e) {

        }

        return XORCipher::cipher($chk, $key);
    }
}
