<?php

namespace App\Http\Controllers\Web\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Tools\Account\LinkApiRequest;
use App\Http\Requests\Web\Tools\Account\UnlinkApiRequest;
use App\Http\Requests\Web\Tools\Level\TransInApiRequest;
use App\Http\Requests\Web\Tools\Level\TransOutApiRequest;
use App\Http\Requests\Web\Tools\Song\UpdateApiRequest;
use App\Http\Requests\Web\Tools\Song\UploadLinkApiRequest;
use App\Http\Requests\Web\Tools\Song\UploadNeteaseApiRequest;
use App\Models\GameAccount;
use App\Models\GameCustomSong;
use App\Services\Web\Tools\AccountService;
use App\Services\Web\Tools\LevelService;
use App\Services\Web\Tools\SongService;
use Illuminate\Support\Facades\Auth;
use Inertia\Response as InertiaResponse;

/**
 * Class ApiController
 * @package App\Http\Controllers\Web\Tools
 */
class ApiController extends Controller
{
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var SongService
     */
    protected $songService;

    /**
     * @var LevelService
     */
    protected $levelService;

    /**
     * WebToolsApiController constructor.
     * @param AccountService $accountService
     * @param SongService $songService
     * @param LevelService $levelService
     */
    public function __construct(AccountService $accountService, SongService $songService, LevelService $levelService)
    {
        $this->accountService = $accountService;
        $this->songService = $songService;
        $this->levelService = $levelService;
    }

    /**
     * @param LinkApiRequest $request
     * @return InertiaResponse
     */
    public function linkAccount(LinkApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->accountService->linkAccount($data['server'], $data['target_name'], $data['target_password']);
    }

    /**
     * @param UnlinkApiRequest $request
     * @return InertiaResponse
     */
    public function unlinkAccount(UnlinkApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->accountService->unlinkAccount($data['id']);
    }

    /**
     * @param UploadLinkApiRequest $request
     * @return InertiaResponse
     */
    public function CustomSongUpload_Link(UploadLinkApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->songService->upload_link($data['song_id'], $data['name'], $data['author_name'], $data['link']);
    }

    /**
     * @param UploadNeteaseApiRequest $request
     * @return InertiaResponse
     */
    public function CustomSongUpload_NeteaseMusic(UploadNeteaseApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->songService->upload_netease($data['song_id'], $data['music_id']);
    }

    /**
     * @param GameCustomSong $song
     * @return InertiaResponse
     */
    public function deleteSong(GameCustomSong $song): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        return $this->songService->deleteSong($account, $song);
    }

    /**
     * @param GameCustomSong $song
     * @param UpdateApiRequest $request
     * @return InertiaResponse
     */
    public function updateSong(GameCustomSong $song, UpdateApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->songService->updateSong(
            $request->user(),
            $song,
            $data['song_id'],
            $data['name'],
            $data['author_name']
        );
    }

    /**
     * @param TransInApiRequest $request
     * @return InertiaResponse
     */
    public function levelTransIn(TransInApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->levelService->transIn($data['server'], $data['levelID']);
    }

    /**
     * @param TransOutApiRequest $request
     * @return InertiaResponse
     */
    public function levelTransOut(TransOutApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->levelService->transOut(
            $data['server'],
            $data['levelID'],
            $data['songType'],
            $data['songID'] ?? null,
            $data['password']
        );
    }
}
