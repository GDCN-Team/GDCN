<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\NGProxy\SongInfoGetRequest;
use App\Services\Web\NGProxyService;
use Inertia\Response;

class NGProxyApiController extends Controller
{
    public function __construct(
        public NGProxyService $service
    )
    {
    }

    public function getSongInfo(SongInfoGetRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->getSongInfo($data['songID']);
    }
}
