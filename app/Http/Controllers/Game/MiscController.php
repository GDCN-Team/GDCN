<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\LikeType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Item\LikeRequest;
use App\Http\Requests\Game\Item\RestoreRequest;
use App\Services\Game\MiscService;
use Illuminate\Support\Arr;

class MiscController extends Controller
{
    public function __construct(
        public MiscService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/likeGJItem211
     * @throws InvalidArgumentException
     */
    public function likeItem(LikeRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->like(Arr::getAny($data, ['accountID', 'udid']), LikeType::fromValue($data['type']), $data['itemID'], $data['like'])) {
            return ResponseCode::LIKE_FAILED;
        }

        return ResponseCode::LIKE_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/restoreGJItems
     */
    public function restoreItem(RestoreRequest $request): int
    {
        $request->validated();
        if (!$result = $this->service->restore()) {
            return ResponseCode::RESTORE_ITEM_FAILED;
        }

        return $result;
    }
}
