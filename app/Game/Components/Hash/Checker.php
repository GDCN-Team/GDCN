<?php

namespace App\Game\Components\Hash;

use App\Http\Requests\Game\Account\Comment\UploadRequest as AccountCommentUploadRequest;
use App\Http\Requests\Game\Item\LikeRequest;
use App\Http\Requests\Game\Level\Comment\UploadRequest as LevelCommentUploadRequest;
use App\Http\Requests\Game\Level\DownloadRequest;
use App\Http\Requests\Game\Level\Rating\RateStarsRequest;
use App\Http\Requests\Game\User\Score\UpdateRequest;
use GDCN\ChkValidationException;
use GDCN\Hash;

/**
 * Class Checker
 * @package App\Game\Components\Hash
 */
class Checker
{
    /**
     * @param DownloadRequest $request
     * @throws ChkValidationException
     */
    public static function downloadLevel(DownloadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForDownloadLevel($data['levelID'], $data['inc'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            Hash::decode($data['chk'], Hash::$keys['level_seed'])
        );
    }

    /**
     * @param AccountCommentUploadRequest $request
     * @throws ChkValidationException
     */
    public static function uploadLevel(AccountCommentUploadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateSeed2ForUploadLevel($data['levelString']),
            Hash::decode($data['seed2'], Hash::$keys['level_seed'])
        );
    }

    /**
     * @param AccountCommentUploadRequest $request
     * @throws ChkValidationException
     */
    public static function uploadAccountComment(AccountCommentUploadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForUploadAccountComment($data['userName'], $data['comment']),
            Hash::decode($data['chk'], Hash::$keys['comment'])
        );
    }

    /**
     * @param LevelCommentUploadRequest $request
     * @throws ChkValidationException
     */
    public static function uploadLevelComment(LevelCommentUploadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForUploadLevelComment($data['userName'], $data['comment'], $data['levelID'], $data['percent'] ?? 0),
            Hash::decode($data['chk'], Hash::$keys['comment'])
        );
    }

    /**
     * @param LikeRequest $request
     * @throws ChkValidationException
     */
    public static function like(LikeRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForLike($data['special'], $data['itemID'], $data['like'], $data['type'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            Hash::decode($data['chk'], Hash::$keys['like'])
        );
    }

    /**
     * @param RateStarsRequest $request
     * @throws ChkValidationException
     */
    public static function rate(RateStarsRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForRate($data['levelID'], $data['stars'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            Hash::decode($data['chk'], Hash::$keys['rate'])
        );
    }

    /**
     * @param UpdateRequest $request
     * @throws ChkValidationException
     */
    public static function uploadUserScore(UpdateRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForUploadUserScore($data['accountID'], $data['userCoins'], $data['demons'], $data['stars'], $data['coins'], $data['iconType'], $data['icon'], $data['diamonds'], $data['accIcon'], $data['accShip'], $data['accBall'], $data['accBird'], $data['accDart'], $data['accRobot'], $data['accGlow'], $data['accSpider'], $data['accExplosion']),
            Hash::decode($data['seed2'], Hash::$keys['user'])
        );
    }
}
