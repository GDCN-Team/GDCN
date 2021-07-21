<?php

namespace App\Services\Web\Tools;

use App\Enums\Web\Tools\Song\Types;
use App\Models\Game\Account;
use App\Models\Game\CustomSong;
use App\Presenter\Web\ToolsPresenter;
use App\Services\Web\NoticeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Response;

/**
 * Class SongService
 * @package App\Services
 */
class SongService
{
    /**
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var ToolsPresenter
     */
    protected $presenter;

    /**
     * SongService constructor.
     * @param ToolsPresenter $presenter
     * @param NoticeService $noticeService
     */
    public function __construct(ToolsPresenter $presenter, NoticeService $noticeService)
    {
        $this->presenter = $presenter;
        $this->noticeService = $noticeService;
    }

    /**
     * @param int $songID
     * @param int $musicID
     * @return Response
     */
    public function upload_netease(int $songID, int $musicID): Response
    {
        if (CustomSong::whereSongId($songID)->exists()) {
            $this->noticeService->sendErrorNotice("歌曲ID $songID 已被使用");
        } else {
            $data = Http::get("https://music.163.com/api/song/detail?ids=[$musicID]")->json();
            if (empty($data['songs'])) {
                $this->noticeService->sendErrorNotice('歌曲不存在(或未找到)');
            } else {
                $music = $data['songs'][0];
                $hash = sha1("netease:{$music['id']}");

                $artists = [];
                foreach ($music['artists'] as $artist) {
                    $artists[] = $artist['name'];
                }

                $s = CustomSong::whereHash($hash);
                if ($s->exists()) {
                    $this->noticeService->sendErrorNotice("歌曲 {$music['name']} 已被上传过了", "歌曲ID: {$s->value('song_id')}");
                } else {
                    $song = new CustomSong();
                    $song->song_id = $songID;
                    $song->type = Types::NETEASE_MUSIC;
                    $song->name = $music['name'];
                    $song->author_name = implode(' / ', $artists);
                    $song->size = round($music['lMusic']['size'] / 1024 / 1024, 2);
                    $song->download_url = "https://music.163.com/song/media/outer/url?id={$music['id']}.mp3";
                    $song->hash = $hash;
                    $song->uploader = Auth::id();
                    $song->disabled = false;
                    $song->save();

                    $this->noticeService->sendSuccessNotice('歌曲上传成功!', "歌曲ID: $song->song_id");
                }
            }
        }

        return $this->presenter->uploadNetease();
    }

    /**
     * @param Account $operator
     * @param CustomSong $song
     * @return Response
     */
    public function deleteSong(Account $operator, CustomSong $song): Response
    {
        if ($operator->can('delete', $song)) {
            $song->delete();
            $this->noticeService->sendSuccessNotice('删除成功!');
        } else {
            $this->noticeService->sendErrorNotice('删除失败');
        }

        return $this->presenter->songList();
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
        $s = CustomSong::whereDownloadUrl($link);
        if ($s->exists()) {
            $this->noticeService->sendErrorNotice('上传失败', "原因: 歌曲已存在, 歌曲ID: {$s->value('song_id')}");
        } else {
            $request = Http::get($link);
            if (!$request->ok()) {
                $this->noticeService->sendErrorNotice('上传失败', '原因: 链接无法访问');
            } else if (trim(explode('/', $request->header('Content-Type'))[0]) !== 'audio') {
                $this->noticeService->sendErrorNotice('上传失败', '原因: 不是音频');
            } else {
                $hash = sha1("link:$link");

                $song = CustomSong::whereHash($hash);
                if ($song->exists()) {
                    $this->noticeService->sendErrorNotice('上传失败', "原因: 歌曲已存在, 歌曲ID: {$song->value('song_id')}");
                }

                $song = new CustomSong();
                $song->song_id = $songID;
                $song->type = Types::LINK;
                $song->name = $name;
                $song->author_name = $authorName;
                $song->size = round(strlen($request->body()) / 1024 / 1024, 2);
                $song->download_url = $link;
                $song->hash = $hash;
                $song->uploader = Auth::id();
                $song->disabled = false;
                $song->save();

                $this->noticeService->sendSuccessNotice('歌曲上传成功!', "歌曲ID: $song->song_id");
            }
        }

        return $this->presenter->uploadLink();
    }

    /**
     * @param Account $operator
     * @param CustomSong $song
     * @param $newSongID
     * @param $newName
     * @param $newAuthorName
     * @return Response
     */
    public function updateSong(Account $operator, CustomSong $song, $newSongID, $newName, $newAuthorName): Response
    {
        if ($operator->can('edit', $song)) {
            $song->song_id = $newSongID;
            $song->name = $newName;
            $song->author_name = $newAuthorName;
            $song->save();

            $this->noticeService->sendSuccessNotice('编辑成功!');
        } else {
            $this->noticeService->sendErrorNotice('编辑失败');
        }

        return $this->presenter->songList();
    }
}
