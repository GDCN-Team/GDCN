<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Enums\LikeType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Item\LikeRequest;
use App\Http\Requests\Game\Item\RestoreRequest;
use App\Services\Game\MiscService;

class MiscController extends Controller
{
    /**
     * @param MiscService $service
     */
    public function __construct(
        public MiscService $service
    )
    {
    }

    /**
     * @param LikeRequest $request
     * @return int
     *
     * @throws InvalidArgumentException
     * @see http://docs.gdprogra.me/#/endpoints/likeGJItem211
     */
    public function likeItem(LikeRequest $request): int
    {
        $data = $request->validated();
        return $this->service->like(
            $request->getPlayer(),
            LikeType::fromValue((int)$data['type']),
            $data['itemID'],
            $data['like'] ?? true
        ) ? ResponseCode::LIKE_SUCCESS : ResponseCode::LIKE_FAILED;
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
        $result = $this->service->restore();
        return !empty($result) ? $result : ResponseCode::RESTORE_ITEM_FAILED;
    }
}
