<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class ResponseCode
 *
 * @method static static FAILED()
 * @method static static OK()
 * @method static static LOGIN_FAILED()
 * @method static static EMPTY_RESULT_FAILED()
 * @method static static EMPTY_RESULT()
 * @method static static REQUEST_CHECK_FAILED()
 * @method static static INVALID_REQUEST()
 * @method static static UNHANDLED_EXCEPTION()
 * @method static static CHK_CHECK_FAILED()
 * @method static static USER_NOT_FOUND()
 * @method static static ACCOUNT_NOT_FOUND()
 * @method static static MESSAGE_NOT_FOUND()
 * @method static static LEVEL_NOT_FOUND()
 * @method static static LEVEL_COMMENT_NOT_FOUND()
 * @method static static ACCOUNT_COMMENT_NOT_FOUND()
 * @method static static FRIEND_REQUEST_NOT_FOUND()
 * @method static static BLOCK_NOT_FOUND()
 * @method static static SAVE_FAILED()
 * @method static static PERMISSION_DENIED()
 * @method static static CHALLENGE_GENERATE_FAILED()
 * @method static static ACCOUNT_REGISTER_USERNAME_IS_ALREADY_IN_USE()
 * @method static static ACCOUNT_REGISTER_USERNAME_IS_INVALID()
 * @method static static ACCOUNT_REGISTER_PASSWORD_IS_INVALID()
 * @method static static ACCOUNT_REGISTER_EMAIL_IS_ALREADY_IN_USE()
 * @method static static ACCOUNT_REGISTER_EMAIL_IS_INVALID()
 * @method static static ACCOUNT_REGISTER_EMAIL_IS_TOO_LONG()
 * @method static static ACCOUNT_LOGIN_ACCOUNT_NOT_VERIFIED()
 * @method static static ACCOUNT_LOGIN_FAILED()
 * @method static static DOWNLOAD_LEVEL_MISSING_LEVEL_STRING()
 * @method static static DOWNLOAD_LEVEL_NOT_FOUND()
 * @package App\Enums
 */
final class ResponseCode extends Enum
{
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

    public const SAVE_DATA_EMPTY = self::FAILED;
    public const CHALLENGE_NOT_ENOUGH = self::FAILED;

    // Account register
    public const ACCOUNT_REGISTER_USERNAME_IS_ALREADY_IN_USE = -2;
    public const ACCOUNT_REGISTER_EMAIL_IS_ALREADY_IN_USE = -3;
    public const ACCOUNT_REGISTER_EMAIL_IS_INVALID = -6;

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
