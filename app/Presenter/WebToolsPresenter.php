<?php

namespace App\Presenter;

use App\Enums\Web\Tools\Song\Types;
use App\Models\GameAccountLink;
use App\Models\GameCustomSong;
use App\Repositories\Game\CustomSongRepository;
use App\Services\Web\NoticeService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class WebToolsPresenter
 * @package App\Presenter
 */
class WebToolsPresenter
{
    /**
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var CustomSongRepository
     */
    protected $repository;

    /**
     * WebToolsPresenter constructor.
     * @param NoticeService $noticeService
     * @param CustomSongRepository $repository
     */
    public function __construct(NoticeService $noticeService, CustomSongRepository $repository)
    {
        $this->noticeService = $noticeService;
        $this->repository = $repository;
    }

    /**
     * @return Response
     */
    public function accountLink(): Response
    {
        return Inertia::render('Tools/Account/Link', [
            'links' => GameAccountLink::whereAccount(Auth::id())
                ->get(['id', 'target_name'])
        ]);
    }

    /**
     * @return Response
     */
    public function songList(): Response
    {
        return Inertia::render('Tools/Song/List', [
            'songs' => $this->repository->getForSongList(),
            'editableTypes' => [Types::LINK]
        ]);
    }

    /**
     * @return Response
     */
    public function uploadNetease(): Response
    {
        return Inertia::render('Tools/Song/NeteaseUpload', [
            'latestSongID' => function () {
                $songID = GameCustomSong::query()
                    ->latest()
                    ->value('song_id');

                $config = config('game.customSongIdOffset');
                return $songID < $config ? $config : ($songID + 1);
            }
        ]);
    }

    /**
     * @return Response
     */
    public function uploadLink(): Response
    {
        return Inertia::render('Tools/Song/LinkUpload', [
            'latestSongID' => function () {
                $songID = GameCustomSong::query()
                    ->latest()
                    ->value('song_id');

                $config = config('game.customSongIdOffset');
                return $songID < $config ? $config : ($songID + 1);
            }
        ]);
    }

    /**
     * @return Response
     */
    public function levelTransIn(): Response
    {
        return Inertia::render('Tools/Level/TransIn');
    }

    /**
     * @return Response
     */
    public function levelTransOut(): Response
    {
        return Inertia::render('Tools/Level/TransOut');
    }
}
