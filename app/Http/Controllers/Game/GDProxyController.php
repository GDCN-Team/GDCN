<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\SongNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\Game\GDProxyService;
use Illuminate\Http\Request;

class GDProxyController extends Controller
{
    public function __construct(
        public GDProxyService $service
    )
    {
    }

    public function proxy(Request $request): string
    {
        try {
            return $this->service->proxy(
                $request->getRequestUri(),
                $request->all()
            );
        } catch (SongNotFoundException) {
            return ResponseCode::SONG_NOT_FOUND;
        }
    }
}
