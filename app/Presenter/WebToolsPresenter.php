<?php

namespace App\Presenter;

use App\Enums\GameCustomSongType;
use App\Enums\GameOtherServerAliasEnum;
use App\Models\GameAccountLink;
use App\Models\GameCustomSong;
use App\Repositories\GameCustomSongRepository;
use App\Services\WebNoticeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class WebToolsPresenter
 * @package App\Presenter
 */
class WebToolsPresenter
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * @var GameCustomSongRepository
     */
    protected $repository;

    /**
     * WebToolsPresenter constructor.
     * @param WebNoticeService $noticeService
     * @param GameCustomSongRepository $repository
     */
    public function __construct(WebNoticeService $noticeService, GameCustomSongRepository $repository)
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
                ->get(['id', 'host', 'target_name'])
                ->map(function ($link) {
                    $host = strtr($link->host, ['.' => '_']);
                    $host = Str::upper($host);
                    $link->host = GameOtherServerAliasEnum::getValue($host);
                    return $link;
                })->toArray()
        ]);
    }

    /**
     * @return Response
     */
    public function songList(): Response
    {
        return Inertia::render('Tools/Song/List', [
            'songs' => $this->repository->getForSongList(),
            'editableTypes' => [GameCustomSongType::LINK]
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
}
