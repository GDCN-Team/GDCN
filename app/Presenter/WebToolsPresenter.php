<?php

namespace App\Presenter;

use App\Enums\GameCustomSongType;
use App\Models\GameCustomSong;
use App\Services\WebNoticeService;
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
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * WebToolsPresenter constructor.
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebNoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @param array $links
     * @return Response
     */
    public function accountLink(array $links): Response
    {
        return Inertia::render('Tools/Account/Link', [
            'links' => $links
        ]);
    }

    /**
     * @param $songs
     * @return Response
     */
    public function songList($songs): Response
    {
        return Inertia::render('Tools/Song/List', [
            'songs' => $songs
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

    public function editSong($id)
    {
        if (!$song = GameCustomSong::whereId($id)->first()) {
            return Inertia::location(route('tools.song.list'));
        }

        if (!$song->uploader === Auth::id()) {
            $this->noticeService->sendErrorNotice('您不是歌曲上传者');
            return Inertia::location(route('tools.song.list'));
        }

        if ($song->type === GameCustomSongType::NETEASE_MUSIC) {
            $this->noticeService->sendErrorNotice('该歌曲不可编辑');
            return Inertia::location(route('tools.song.list'));
        }

        $songs = GameCustomSong::query()
            ->with('owner:id,name')
            ->get(['id', 'name', 'author_name', 'download_url', 'song_id', 'size', 'uploader']);

        return Inertia::render('Tools/Song/Edit', [
            'song' => $song,
            'songs' => $songs
        ]);
    }
}
