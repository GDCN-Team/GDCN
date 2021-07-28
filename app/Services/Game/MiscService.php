<?php

namespace App\Services\Game;

use App\Enums\Game\Log\Types;
use App\Enums\LikeType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\Log;
use Illuminate\Support\Facades\Request;

/**
 * Class MiscService
 * @package App\Services\Game
 */
class MiscService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    /**
     * @param $user
     * @param LikeType $type
     * @param int $itemID
     * @param bool $like
     * @return bool
     * @throws InvalidArgumentException
     */
    public function like($user, LikeType $type, int $itemID, bool $like = true): bool
    {
        switch ($type->value) {
            case LikeType::LEVEL:
                $item = Level::findOrFail($itemID);
                $logType = Types::LIKE_LEVEL;
                break;
            case LikeType::LEVEL_COMMENT:
                $item = LevelComment::findOrFail($itemID);
                $logType = Types::LIKE_LEVEL_COMMENT;
                break;
            case LikeType::ACCOUNT_COMMENT:
                $item = AccountComment::findOrFail($itemID);
                $logType = Types::LIKE_ACCOUNT_COMMENT;
                break;
            default:
                throw new InvalidArgumentException();
        }

        $log = Log::firstOrNew([
            'type' => $logType,
            'user' => $this->helper->getID($user),
            'value' => $itemID,
            'ip' => Request::ip()
        ]);

        if (!$log->exists()) {
            $log->save();

            if ($like) {
                ++$item->likes;
            } else {
                --$item->likes;
            }

            return $item->save();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function restore(): bool
    {
        return false;
    }
}
