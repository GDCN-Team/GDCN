<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebToolsAccountLinkApiRequest;
use App\Http\Requests\WebToolsAccountUnlinkApiRequest;
use App\Http\Requests\WebToolsLevelTransInApiRequest;
use App\Http\Requests\WebToolsLevelTransOutApiRequest;
use App\Http\Requests\WebToolsSongDeleteApiRequest;
use App\Http\Requests\WebToolsSongUpdateApiRequest;
use App\Http\Requests\WebToolsSongUploadLinkApiRequest;
use App\Http\Requests\WebToolsSongUploadNeteaseApiRequest;
use App\Services\WebToolsLevelService;
use App\Services\WebToolsService;
use App\Services\WebToolsSongService;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

/**
 * Class WebToolsApiController
 * @package App\Http\Controllers
 */
class WebToolsApiController extends Controller
{
    /**
     * @var WebToolsService
     */
    protected $service;

    /**
     * @var WebToolsSongService
     */
    protected $songService;

    /**
     * @var WebToolsLevelService
     */
    protected $levelService;

    /**
     * WebToolsApiController constructor.
     * @param WebToolsService $service
     * @param WebToolsSongService $songService
     * @param WebToolsLevelService $levelService
     */
    public function __construct(WebToolsService $service, WebToolsSongService $songService, WebToolsLevelService $levelService)
    {
        $this->service = $service;
        $this->songService = $songService;
        $this->levelService = $levelService;
    }

    /**
     * @param WebToolsAccountLinkApiRequest $request
     * @return Response
     */
    public function linkAccount(WebToolsAccountLinkApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->linkAccount($data['server'], $data['target_name'], $data['target_password']);
    }

    /**
     * @param WebToolsAccountUnlinkApiRequest $request
     * @return Response
     */
    public function unlinkAccount(WebToolsAccountUnlinkApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->unlinkAccount($data['id']);
    }

    /**
     * @param WebToolsSongUploadLinkApiRequest $request
     * @return InertiaResponse
     */
    public function CustomSongUpload_Link(WebToolsSongUploadLinkApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->songService->upload_link($data['song_id'], $data['name'], $data['author_name'], $data['link']);
    }

    /**
     * @param WebToolsSongUploadNeteaseApiRequest $request
     * @return InertiaResponse
     */
    public function CustomSongUpload_NeteaseMusic(WebToolsSongUploadNeteaseApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->songService->upload_netease($data['song_id'], $data['music_id']);
    }

    /**
     * @param WebToolsSongDeleteApiRequest $request
     * @return Response
     */
    public function deleteSong(WebToolsSongDeleteApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->songService->deleteSong($data['id']);
    }

    /**
     * @param WebToolsSongUpdateApiRequest $request
     * @return Response
     */
    public function updateSong(WebToolsSongUpdateApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->songService->updateSong($data['id'], $data['song_id'], $data['name'], $data['author_name']);
    }

    /**
     * @param WebToolsLevelTransInApiRequest $request
     * @return InertiaResponse
     */
    public function levelTransIn(WebToolsLevelTransInApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->levelService->transIn($data['server'], $data['levelID']);
    }

    /**
     * @param WebToolsLevelTransOutApiRequest $request
     * @return InertiaResponse
     */
    public function levelTransOut(WebToolsLevelTransOutApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->levelService->transOut($data['server'], $data['levelID']);
    }
}
