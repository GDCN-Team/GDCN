<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\Log\Types;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\ChkValidateException;
use App\Exceptions\Game\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Item\LikeRequest;
use App\Http\Requests\Game\Item\RestoreRequest;
use App\Models\Game\Account\Comment;
use App\Models\Game\Level;
use App\Models\Game\Log;

/**
 * Class MiscController
 * @package App\Http\Controllers
 */
class MiscController extends Controller
{
    /**
     * @param LikeRequest $request
     * @param HashesController $hash
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/likeGJItem211
     */
    public function likeItem(LikeRequest $request, HashesController $hash): ?int
    {
        try {
            $data = $request->validated();

            $hash->checkChk(
                $hash->generateLikeChk($data['special'], $data['itemID'], $data['like'], $data['type'], $data['rs'], ($data['accountID'] ?? 0), $data['udid'], $data['uuid']),
                $hash->decodeChk($data['chk'], $hash->keys['like'])
            );

            switch ($data['type']) {
                case 1: // Level
                    $item = Level::whereId($data['itemID'])->first();
                    $logType = Types::LIKE_LEVEL;
                    break;
                case 2: // Level Comment
                    $item = Comment::whereId($data['itemID'])->first();
                    $logType = Types::LIKE_LEVEL_COMMENT;
                    break;
                case 3: // Account Comment
                    $item = Comment::whereId($data['itemID'])->first();
                    $logType = Types::LIKE_ACCOUNT_COMMENT;
                    break;
                default:
                    return ResponseCode::INVALID_REQUEST;
            }

            if (!$item) {
                return ResponseCode::ITEM_NOT_FOUND;
            }

            $user = $request->user;
            if (!$user) {
                return ResponseCode::USER_NOT_FOUND;
            }

            $attributes = [
                'type' => $logType,
                'value' => $item->id,
                'user' => $user->id
            ];

            $ip = [
                'ip' => $request->ip()
            ];

            $log = Log::query()
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
        } catch (ChkValidateException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        } catch (UserNotFoundException $e) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }

    /**
     * @param RestoreRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/restoreGJItems
     */
    public function restoreItem(RestoreRequest $request): int
    {
        $request->validated();
        return ResponseCode::RESTORE_ITEM_FAILED;
    }
}
