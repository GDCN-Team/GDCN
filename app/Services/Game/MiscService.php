<?php

namespace App\Services\Game;

use App\Enums\Game\LikeType;
use App\Enums\Game\LogType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Request;

class MiscService
{
    public function __construct(
        public HelperService $helper
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function like(?string $uuid, LikeType $type, int $itemID, bool $like = true): bool
    {
        $user = $this->helper->resolveUser($uuid);

        switch ($type->value) {
            case LikeType::LEVEL:
                $item = Level::find($itemID);
                $logType = LogType::LIKE_LEVEL;
                break;
            case LikeType::LEVEL_COMMENT:
                $item = LevelComment::find($itemID);
                $logType = LogType::LIKE_LEVEL_COMMENT;
                break;
            case LikeType::ACCOUNT_COMMENT:
                $item = AccountComment::find($itemID);
                $logType = LogType::LIKE_ACCOUNT_COMMENT;
                break;
            default:
                throw new InvalidArgumentException();
        }

        Log::where([
            'type' => $logType,
            'user' => $user->id,
            'value' => $itemID,
            'ip' => Request::ip()
        ])->existsOr(function () use (&$item, $like, $itemID, $user, $logType) {
            /** @var Level|LevelComment|AccountComment|Builder $item */

            Log::create([
                'type' => $logType,
                'user' => $user->id,
                'value' => $itemID,
                'ip' => Request::ip()
            ]);

            if ($like === true) {
                ++$item->likes;
            } else {
                --$item->likes;
            }
        });

        LogFacade::channel('gdcn')
            ->info('[Misc System] Action: Like Item', [
                'userID' => $user->id,
                'type' => $type->value,
                'itemID' => $itemID,
                'like' => $like
            ]);

        return $item->save();
    }

    public function restore(): bool
    {
        LogFacade::channel('gdcn')
            ->info('[Misc System] Action: Restore Item');

        return false;
    }
}
