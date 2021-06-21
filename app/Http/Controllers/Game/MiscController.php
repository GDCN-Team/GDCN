<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\LikeType;
use App\Enums\Game\LogType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\GameChkValidateException;
use App\Exceptions\GameUserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameItemLikeRequest;
use App\Http\Requests\GameItemRestoreRequest;
use App\Models\GameAccountComment;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use App\Models\GameLog;

/**
 * Class MiscController
 * @package App\Http\Controllers
 */
class MiscController extends Controller
{
    /**
     * @param GameItemLikeRequest $request
     * @param HashesController $hash
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/likeGJItem211
     */
    public function likeItem(GameItemLikeRequest $request, HashesController $hash): ?int
    {
        try {
            $data = $request->validated();

            $hash->checkChk(
                $hash->generateLikeChk($data['special'], $data['itemID'], $data['like'], $data['type'], $data['rs'], ($data['accountID'] ?? 0), $data['udid'], $data['uuid']),
                $hash->decodeChk($data['chk'], $hash->keys['like'])
            );

            switch ($data['type']) {
                case LikeType::LEVEL:
                    $item = GameLevel::whereId($data['itemID'])->first();
                    $logType = LogType::LIKE_LEVEL;
                    break;
                case LikeType::LEVEL_COMMENT:
                    $item = GameLevelComment::whereId($data['itemID'])->first();
                    $logType = LogType::LIKE_LEVEL_COMMENT;
                    break;
                case LikeType::ACCOUNT_COMMENT:
                    $item = GameAccountComment::whereId($data['itemID'])->first();
                    $logType = LogType::LIKE_ACCOUNT_COMMENT;
                    break;
                default:
                    return ResponseCode::INVALID_REQUEST;
            }

            if (!$item) {
                return ResponseCode::ITEM_NOT_FOUND;
            }

            $user = $request->getGameUser();
            $attributes = [
                'type' => $logType,
                'value' => $item->id,
                'user' => $user->id
            ];

            $ip = [
                'ip' => $request->ip()
            ];

            $log = GameLog::query()
                ->where($attributes);

            if (!$log->exists()) {
                if ($data['like']) {
                    $item->increment('likes');
                } else {
                    $item->decrement('likes');
                }

                $log->create(array_merge($attributes, $ip));
                $item->save();

                return ResponseCode::OK;
            }

            return ResponseCode::FAILED;
        } catch (GameChkValidateException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        } catch (GameUserNotFoundException $e) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }

    /**
     * @param GameItemRestoreRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/restoreGJItems
     */
    public function restoreItem(GameItemRestoreRequest $request): int
    {
        $request->validated();
        return ResponseCode::RESTORE_ITEM_FAILED;
    }
}
