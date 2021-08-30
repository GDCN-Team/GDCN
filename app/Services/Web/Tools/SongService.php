<?php

namespace App\Services\Web\Tools;

use App\Exceptions\Web\Tools\SongEditException;
use App\Exceptions\Web\Tools\SongUploadLinkException;
use App\Exceptions\Web\Tools\SongUploadNeteaseException;
use App\Models\Game\CustomSong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SongService
{
    /**
     * @return int
     */
    public function getLatestAvailableMinimumID(): int
    {
        $query = CustomSong::orderByDesc('song_id');
        $ids = $query->pluck('song_id')
            ->toArray();

        sort($ids);
        foreach ($ids as $key => $id) {
            $index = config('game.customSongIdOffset') + $key + 1;
            if ($id !== $index) {
                return $index;
            }
        }

        return $query->value('song_id') + 1;
    }

    /**
     * @param int $song_id
     * @param string $name
     * @param string $author_name
     * @param string $link
     * @return bool
     * @throws SongUploadLinkException
     */
    public function uploadLink(int $song_id, string $name, string $author_name, string $link): bool
    {
        if (parse_url($link, PHP_URL_HOST) === 'music.163.com') {
            throw new SongUploadLinkException('检测到网易云音乐链接, 请使用 歌曲上传(网易云专版) 上传');
        }

        $hash = sha1("link:$link");
        if ($song = CustomSong::whereHash($hash)->first()) {
            throw new SongUploadLinkException("该歌曲已被上传过了, 歌曲ID: $song->id");
        }

        $request = Http::get($link);
        if (trim(explode('/', $request->header('Content-Type'))[0]) !== 'audio') {
            throw new SongUploadLinkException('外链内容无效');
        }

        Log::info('Uploaded a custom song', [
            'type' => 'link',
            'accountID' => $accountID = Auth::id(),
            'songID' => $song_id
        ]);

        return CustomSong::insert([
            'type' => 'link',
            'uploader' => $accountID,
            'song_id' => $song_id,
            'name' => $name,
            'author_name' => $author_name,
            'hash' => $hash,
            'size' => round(strlen($request->body()) / 1024 / 1024, 2),
            'download_url' => $link,
            'disabled' => false
        ]);
    }

    /**
     * @param int $song_id
     * @param int $musicID
     * @return bool
     * @throws SongUploadNeteaseException
     */
    public function uploadNetease(int $song_id, int $musicID): bool
    {
        $hash = sha1("netease:$musicID");
        if ($song = CustomSong::whereHash($hash)->first()) {
            throw new SongUploadNeteaseException("该歌曲已被上传过了, 歌曲ID: $song->id");
        }

        $songs = Http::get("https://music.163.com/api/song/detail?ids=[$musicID]")->json('songs');
        $song = $songs[0];

        Log::info('Uploaded a custom song', [
            'type' => 'netease',
            'accountID' => $accountID = Auth::id(),
            'songID' => $song_id
        ]);

        $artists = collect($song['artists'])
            ->map(function ($artist) {
                return $artist['name'];
            })->join(' / ');

        return CustomSong::insert([
            'type' => 'netease',
            'uploader' => $accountID,
            'song_id' => $song_id,
            'name' => $song['name'],
            'author_name' => $artists,
            'hash' => $hash,
            'size' => round($song['lMusic']['size'] / 1024 / 1024, 2),
            'download_url' => "https://music.163.com/song/media/outer/url?id={$song['id']}.mp3",
            'disabled' => false
        ]);
    }

    /**
     * @param CustomSong $song
     * @return bool|null
     */
    public function delete(CustomSong $song): ?bool
    {
        $account = Auth::user();
        if ($song->getRelationValue('uploader')->is($account)) {
            return $song->delete();
        }

        return false;
    }

    /**
     * @param int $songID
     * @param string $name
     * @param string $author_name
     * @return bool
     * @throws SongEditException
     */
    public function edit(int $songID, string $name, string $author_name): bool
    {
        if (!$song = CustomSong::find($songID)) {
            return false;
        }

        $account = Auth::user();
        if (!$song->owner->is($account)) {
            throw new SongEditException('该歌曲不属于你');
        }

        if ($song->type === 'netease') {
            throw new SongEditException('该歌曲不可修改');
        }

        return $song->update([
            'name' => $name,
            'author_name' => $author_name
        ]);
    }
}
