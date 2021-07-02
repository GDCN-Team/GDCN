<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

/**
 * Class ResponseCode
 * @package App\Enums\Game
 */
final class ResponseCode extends Enum
{
    //Account register
    public const REGISTER_USERNAME_NOT_UNIQUE = -2;
    public const REGISTER_EMAIL_NOT_UNIQUE = -3;
    public const REGISTER_EMAIL_INVALID = -6;

    //Account block
    public const BLOCK_FAILED = self::FAILED;
    public const BLOCK_SUCCESS = self::OK;
    public const UNBLOCK_SUCCESS = self::OK;
    public const UNBLOCK_FAILED = self::DELETE_FAILED;

    public const FAILED = -1;
    public const OK = 1;

    public const LOGIN_FAILED = self::FAILED;

    public const EMPTY_RESULT_FAILED = self::FAILED;
    public const EMPTY_RESULT = -2;

    public const REQUEST_CHECK_FAILED = self::FAILED;
    public const INVALID_REQUEST = self::FAILED;
    public const UNHANDLED_EXCEPTION = self::FAILED;
    public const CHK_CHECK_FAILED = self::FAILED;

    public const USER_NOT_FOUND = self::FAILED;
    public const ACCOUNT_NOT_FOUND = self::FAILED;
    public const MESSAGE_NOT_FOUND = self::FAILED;
    public const LEVEL_NOT_FOUND = self::FAILED;
    public const LEVEL_COMMENT_NOT_FOUND = self::FAILED;
    public const FRIEND_REQUEST_NOT_FOUND = self::FAILED;
    public const BLOCK_NOT_FOUND = self::FAILED;
    public const ITEM_NOT_FOUND = self::FAILED;
    public const LEVEL_RATING_NOT_FOUND = self::FAILED;

    public const SAVE_FAILED = self::FAILED;
    public const PERMISSION_DENIED = self::FAILED;
    public const CHALLENGE_GENERATE_FAILED = self::FAILED;
    public const FEATURE_NOT_ENABLED = self::FAILED;
    public const ACCOUNT_MUST_IS_NOT_MOD = -2;

    public const AUTH_FAILED = self::FAILED;
    public const DELETE_FAILED = self::FAILED;

    public const SAVE_DATA_NOT_FOUND = self::FAILED;
    public const CHALLENGE_NOT_ENOUGH = self::FAILED;

    // Account login
    public const ACCOUNT_LOGIN_ACCOUNT_NOT_VERIFIED = self::FAILED;
    public const ACCOUNT_LOGIN_FAILED = self::FAILED;

    // Upload level
    public const LEVEL_UPLOAD_FAILED = self::FAILED;

    // Download level
    public const DOWNLOAD_LEVEL_MISSING_LEVEL_STRING = self::FAILED;

    // Restore item
    public const RESTORE_ITEM_FAILED = self::FAILED;
}
