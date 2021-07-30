<?php

namespace App\Enums\Game;

use BenSampo\Enum\Enum;

/**
 * Class ResponseCode
 * @package App\Enums\Game
 */
final class ResponseCode extends Enum
{
    // Account block
    public const BLOCK_FAILED = self::FAILED;
    public const BLOCK_SUCCESS = self::OK;
    public const UNBLOCK_SUCCESS = self::OK;
    public const UNBLOCK_FAILED = self::DELETE_FAILED;

    // Account Comment
    public const ACCOUNT_COMMENT_UPLOAD_FAILED = self::FAILED;
    public const ACCOUNT_COMMENT_DELETE_SUCCESS = self::OK;
    public const ACCOUNT_COMMENT_DELETE_FAILED = self::FAILED;

    // Account Friend Request
    public const FRIEND_REQUEST_UPLOAD_FAILED = self::FAILED;
    public const FRIEND_REQUEST_DELETE_SUCCESS = self::OK;
    public const FRIEND_REQUEST_DELETE_FAILED = self::FAILED;
    public const FRIEND_REQUEST_READ_SUCCESS = self::OK;
    public const FRIEND_REQUEST_READ_FAILED = self::FAILED;
    public const FRIEND_REQUEST_ACCEPT_SUCCESS = self::OK;
    public const FRIEND_REQUEST_ACCEPT_FAILED = self::FAILED;

    // Account Friend
    public const FRIEND_REMOVE_SUCCESS = self::OK;
    public const FRIEND_REMOVE_FAILED = self::FAILED;

    // Account Message
    public const MESSAGE_SENT_SUCCESS = self::OK;
    public const MESSAGE_SENT_FAILED = self::FAILED;
    public const MESSAGE_DELETE_SUCCESS = self::OK;
    public const MESSAGE_DELETE_FAILED = self::FAILED;
    public const MESSAGE_DOWNLOAD_FAILED = self::FAILED;

    // Account Save Data
    public const ACCOUNT_DATA_SAVE_SUCCESS = self::OK;
    public const ACCOUNT_DATA_SAVE_FAILED = self::FAILED;
    public const ACCOUNT_DATA_LOAD_FAILED = self::FAILED;

    // Account Setting
    public const ACCOUNT_SETTING_UPDATE_SUCCESS = self::OK;
    public const ACCOUNT_SETTING_UPDATE_FAILED = self::FAILED;

    // Level Comment
    public const LEVEL_COMMENT_UPLOAD_SUCCESS = self::OK;
    public const LEVEL_COMMENT_UPLOAD_FAILED = self::FAILED;
    public const LEVEL_COMMENT_DELETE_SUCCESS = self::OK;
    public const LEVEL_COMMENT_DELETE_FAILED = self::FAILED;

    // Level Rating
    public const LEVEL_SUGGEST_SUCCESS = self::OK;
    public const LEVEL_SUGGEST_FAILED = self::FAILED;
    public const LEVEL_SUGGEST_RATE_SUCCESS = self::OK;
    public const LEVEL_SUGGEST_RATE_FAILED = self::FAILED;
    public const LEVEL_SUGGEST_DEMON_SUCCESS = self::OK;
    public const LEVEL_SUGGEST_DEMON_FAILED = self::FAILED;

    //Account register
    public const ACCOUNT_REGISTER_SUCCESS = self::OK;
    public const ACCOUNT_REGISTER_FAILED = self::FAILED;
    public const ACCOUNT_REGISTER_USERNAME_NOT_UNIQUE = -2;
    public const ACCOUNT_REGISTER_EMAIL_NOT_UNIQUE = -3;
    public const ACCOUNT_REGISTER_EMAIL_INVALID = -6;

    // Account login
    public const ACCOUNT_LOGIN_ACCOUNT_NOT_VERIFIED = self::FAILED;
    public const ACCOUNT_LOGIN_FAILED = self::FAILED;

    // Level
    public const LEVEL_UPLOAD_FAILED = self::FAILED;
    public const LEVEL_DELETE_SUCCESS = self::OK;
    public const LEVEL_DELETE_FAILED = self::FAILED;
    public const LEVEL_REPORT_SUCCESS = self::OK;
    public const LEVEL_REPORT_FAILED = self::FAILED;
    public const LEVEL_UPDATE_DESC_SUCCESS = self::OK;
    public const LEVEL_UPDATE_DESC_FAILED = self::FAILED;
    public const LEVEL_EMPTY_RESULT_STRING = '##' . self::LEVEL_PACK_EMPTY_RESULT_STRING;

    // Level Pack
    public const LEVEL_PACK_EMPTY_RESULT_STRING = self::EMPTY_RESULT_STRING . '#f5da5823d94bbe7208dd83a30ff427c7d88fdb99';

    // Misc
    public const LIKE_SUCCESS = self::OK;
    public const LIKE_FAILED = self::FAILED;
    public const RESTORE_ITEM_FAILED = self::FAILED;

    // Challenge
    public const CHALLENGE_GET_FAILED = self::FAILED;

    // Song
    public const SONG_GET_FAILED = self::FAILED;
    public const SONG_DISABLED = -2;

    // User
    public const ACCESS_FAILED = self::FAILED;

    // User Score
    public const USER_SCORE_UPDATE_SUCCESS = self::OK;
    public const USER_SCORE_UPDATE_FAILED = self::FAILED;

    public const FAILED = -1;
    public const OK = 1;

    public const LOGIN_FAILED = self::FAILED;

    public const EMPTY_RESULT_FAILED = self::FAILED;
    public const EMPTY_RESULT = -2;
    public const EMPTY_RESULT_STRING = '#0:0:10';

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


    // Download level
    public const DOWNLOAD_LEVEL_MISSING_LEVEL_STRING = self::FAILED;

    // Misc
    public const USER_SCORE_NOT_FOUND = self::FAILED;
}
