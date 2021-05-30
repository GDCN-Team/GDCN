<?php

namespace App\Services;

use App\Enums\GameCustomSongType;
use App\Models\GameAccount;
use App\Models\GameCustomSong;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class WebToolsSongService
 * @package App\Services
 */
class WebToolsSongService
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * WebToolsSongService constructor.
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebNoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @param int $songID
     * @param int $musicID
     * @return Response
     */
    public function upload_netease(int $songID, int $musicID): Response
    {
        if (GameCustomSong::whereSongId($songID)->exists()) {
            $this->noticeService->sendErrorNotice("歌曲ID {$songID} 已被使用");
        } else {
            $data = Http::get("http://music.163.com/api/song/detail?ids=[$musicID]")->json();
            if (empty($data['songs'])) {
                $this->noticeService->sendErrorNotice("歌曲不存在(或未找到)");
            } else {
                $music = $data['songs'][0];
                $hash = sha1("netease:{$music['id']}");

                $artists = [];
                foreach ($music['artists'] as $artist) {
                    $artists[] = $artist['name'];
                }

                $s = GameCustomSong::whereHash($hash);
                if ($s->exists()) {
                    $this->noticeService->sendErrorNotice("歌曲 {$music['name']} 已被上传过了", "歌曲ID: $s->song_id");
                } else {
                    $song = new GameCustomSong;
                    $song->song_id = $songID;
                    $song->type = GameCustomSongType::NETEASE_MUSIC;
                    $song->name = $music['name'];
                    $song->author_name = implode(' / ', $artists);
                    $song->size = round($music['lMusic']['size'] / 1024 / 1024, 2);
                    $song->download_url = "http://music.163.com/song/media/outer/url?id={$music['id']}.mp3";
                    $song->hash = $hash;
                    $song->uploader = Auth::id();
                    $song->disabled = false;
                    $song->save();

                    $this->noticeService->sendSuccessNotice('歌曲上传成功!', "歌曲ID: $song->song_id");
                }
            }
        }

        $this->noticeService->loadNotices();
        return Inertia::render('Tools/Song/NeteaseUpload');
    }

    /**
     * @param $id
     * @return HttpResponse
     */
    public function deleteSong($id): HttpResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        $song = GameCustomSong::whereId($id)->first();
        if (!$song) {
            $this->noticeService->sendErrorNotice('歌曲不存在(或未找到)');
        } else if ($song->uploader === $account->id) {
            $song->delete();
            $this->noticeService->sendSuccessNotice('删除成功!');
        } else {
            $this->noticeService->sendErrorNotice('删除失败', '原因: 这个歌曲不是你上传的');
        }

        $this->noticeService->loadNotices();
        return Inertia::location(route('tools.song.list'));
    }

    /**
     * @param $songID
     * @param $name
     * @param $authorName
     * @param $link
     * @return Response
     */
    public function upload_link($songID, $name, $authorName, $link): Response
    {
        $song = GameCustomSong::whereDownloadUrl($link);
        if ($song->exists()) {
            $this->noticeService->sendErrorNotice('上传失败', "原因: 歌曲已存在, 歌曲ID: {$song->value('song_id')}");
        } else {
            $request = Http::get($link);
            if (!$request->ok()) {
                $this->noticeService->sendErrorNotice('上传失败', '原因: 链接无法访问');
            } else if (trim(explode('/', $request->header('Content-Type'))[0]) !== 'audio') {
                $this->noticeService->sendErrorNotice('上传失败', '原因: 不是音频');
            } else {
                $hash = sha1("link:{$link}");

                $song = GameCustomSong::whereHash($hash);
                if ($song->exists()) {
                    $this->noticeService->sendErrorNotice('上传失败', "原因: 歌曲已存在, 歌曲ID: {$song->value('song_id')}");
                }

                $song = new GameCustomSong;
                $song->song_id = $songID;
                $song->type = GameCustomSongType::LINK;
                $song->name = $name;
                $song->author_name = $authorName;
                $song->size = round(strlen($request->body()) / 1024 / 1024, 2);
                $song->download_url = $link;
                $song->hash = $hash;
                $song->uploader = Auth::id();
                $song->disabled = false;
                $song->save();
            }

            $this->noticeService->sendSuccessNotice('歌曲上传成功!', "歌曲ID: $song->song_id");
        }

        $this->noticeService->loadNotices();
        return Inertia::render('Tools/Song/LinkUpload');
    }

    /**
     * @param $id
     * @param $songID
     * @param $name
     * @param $authorName
     * @return HttpResponse
     */
    public function updateSong($id, $songID, $name, $authorName): HttpResponse
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

        $song->song_id = $songID;
        $song->name = $name;
        $song->author_name = $authorName;
        $song->save();

        $this->noticeService->sendSuccessNotice('编辑成功!');
        return Inertia::location(route('tools.song.list'));
    }
}
