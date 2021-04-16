<?php

namespace App\Game\Components\Hash;

use App\Http\Requests\GameAccountCommentUploadRequest;
use App\Http\Requests\GameItemLikeRequest;
use App\Http\Requests\GameLevelCommentUploadRequest;
use App\Http\Requests\GameLevelDownloadRequest;
use App\Http\Requests\GameLevelRatingRateStarsRequest;
use App\Http\Requests\GameLevelUploadRequest;
use App\Http\Requests\GameUserScoreUpdateRequest;
use GDCN\ChkValidationException;
use GDCN\Hash;

/**
 * Class Checker
 * @package App\Game\Components\Hash
 */
class Checker
{
    /**
     * @param GameLevelDownloadRequest $request
     * @throws ChkValidationException
     */
    public static function downloadLevel(GameLevelDownloadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForDownloadLevel($data['levelID'], $data['inc'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            Hash::decode($data['chk'], Hash::$keys['level_seed'])
        );
    }

    /**
     * @param GameLevelUploadRequest $request
     * @throws ChkValidationException
     */
    public static function uploadLevel(GameLevelUploadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateSeed2ForUploadLevel($data['levelString']),
            Hash::decode($data['seed2'], Hash::$keys['level_seed'])
        );
    }

    /**
     * @param GameAccountCommentUploadRequest $request
     * @throws ChkValidationException
     */
    public static function uploadAccountComment(GameAccountCommentUploadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForUploadAccountComment($data['userName'], $data['comment']),
            Hash::decode($data['chk'], Hash::$keys['comment'])
        );
    }

    /**
     * @param GameLevelCommentUploadRequest $request
     * @throws ChkValidationException
     */
    public static function uploadLevelComment(GameLevelCommentUploadRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForUploadLevelComment($data['userName'], $data['comment'], $data['levelID'], $data['percent'] ?? 0),
            Hash::decode($data['chk'], Hash::$keys['comment'])
        );
    }

    /**
     * @param GameItemLikeRequest $request
     * @throws ChkValidationException
     */
    public static function like(GameItemLikeRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForLike($data['special'], $data['itemID'], $data['like'], $data['type'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            Hash::decode($data['chk'], Hash::$keys['like'])
        );
    }

    /**
     * @param GameLevelRatingRateStarsRequest $request
     * @throws ChkValidationException
     */
    public static function rate(GameLevelRatingRateStarsRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForRate($data['levelID'], $data['stars'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            Hash::decode($data['chk'], Hash::$keys['rate'])
        );
    }

    /**
     * @param GameUserScoreUpdateRequest $request
     * @throws ChkValidationException
     */
    public static function uploadUserScore(GameUserScoreUpdateRequest $request): void
    {
        $data = $request->validated();

        Hash::check(
            Hash::generateChkForUploadUserScore($data['accountID'], $data['userCoins'], $data['demons'], $data['stars'], $data['coins'], $data['iconType'], $data['icon'], $data['diamonds'], $data['accIcon'], $data['accShip'], $data['accBall'], $data['accBird'], $data['accDart'], $data['accRobot'], $data['accGlow'], $data['accSpider'], $data['accExplosion']),
            Hash::decode($data['seed2'], Hash::$keys['user'])
        );
    }
}
