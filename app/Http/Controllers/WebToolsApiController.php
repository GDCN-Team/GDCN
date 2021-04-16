<?php

namespace App\Http\Controllers;

use App\Enums\GameCustomSongType;
use App\Game\Helpers;
use App\Game\StorageManager;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\WebToolsApiAccountLinkRequest;
use App\Http\Requests\WebToolsApiAccountUnlinkRequest;
use App\Http\Requests\WebToolsApiDeleteSongRequest;
use App\Http\Requests\WebToolsApiLevelToGdRequest;
use App\Http\Requests\WebToolsApiReuploadLevelRequest;
use App\Http\Requests\WebToolsApiUploadSongNeteaseRequest;
use App\Models\GameAccount;
use App\Models\GameAccountLink;
use App\Models\GameCustomSong;
use App\Models\GameLevel;
use Exception;
use GDCN\GDObject;
use GDCN\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Class WebToolsApiController
 * @package App\Http\Controllers
 */
class WebToolsApiController extends Controller
{
    /**
     * @param WebToolsApiUploadSongNeteaseRequest $request
     * @return array
     */
    public function uploadNeteaseSong(WebToolsApiUploadSongNeteaseRequest $request): array
    {
        $data = $request->validated();
        $response = Http::get("http://musicapi.leanapp.cn/song/detail?ids={$data['musicID']}")->json();
        if (empty($response['songs'])) {
            return $request->failed('歌曲不存在(或未找到)');
        }

        $song = $response['songs'][0];
        if (empty($song)) {
            return $request->failed('歌曲信息获取失败');
        }

        foreach ($song['ar'] as $artist) {
            $authors[] = $artist['name'];
        }

        $hash = sha1("netease:{$song['id']}");
        $songExists = GameCustomSong::whereHash($hash)->exists();
        if ($songExists) {
            return $request->failed("音乐ID {$data['musicID']} 已被使用");
        }

        GameCustomSong::firstOrCreate([
            'song_id' => $data['songID'],
            'type' => GameCustomSongType::NETEASE_MUSIC,
            'name' => $song['name'],
            'author_name' => implode('/', $authors ?? []),
            'size' => round($song['l']['size'] / 1024 / 1024, 2),
            'download_url' => "http://music.163.com/song/media/outer/url?id={$data['musicID']}.mp3",
            'hash' => $hash,
            'uploader' => Auth::id(),
            'disabled' => false
        ]);

        return $request->success();
    }

    /**
     * @param WebToolsApiDeleteSongRequest $request
     * @return array
     */
    public function deleteSong(WebToolsApiDeleteSongRequest $request): array
    {
        try {
            return $request->song->delete() ? $request->success() : $request->failed();
        } catch (Exception $e) {
            return $request->failed($e->getMessage());
        }
    }

    /**
     * @param ApiRequest $request
     * @return array
     */
    public function songList(ApiRequest $request): array
    {
        $accountID = Auth::id();
        $songs = GameCustomSong::whereUploader($accountID)->get();
        return $request->success($songs);
    }

    /**
     * @param WebToolsApiAccountLinkRequest $request
     * @return array
     */
    public function linkAccount(WebToolsApiAccountLinkRequest $request): array
    {
        $data = $request->validated();
        switch ($data['server']) {
            case 'official':
                $url = 'http://dl.geometrydashchinese.com/accounts/loginGJAccount.php';
                break;
            case 'custom':
                $url = $data['custom_server_url'];
                break;
            default:
                return $request->failed('未知服务器类型');
        }

        $host = parse_url($url, PHP_URL_HOST);
        if ($host === 'www.boomlings.com') {
            $host = 'dl.geometrydashchinese.com';
            $url = 'http://dl.geometrydashchinese.com/accounts/loginGJAccount.php';
        }

        $remoteRequest = Http::asForm()
            ->post($url, [
                'userName' => $data['remote_name'],
                'password' => $data['remote_password'],
                'udid' => 'S1145141919810',
                'sID' => 0,
                'secret' => 'Wmfv3899gc9'
            ]);

        if ($remoteRequest->body() === '-1') {
            return $request->failed('登录失败');
        }

        [$accountID, $userID] = explode(',', $remoteRequest->body());
        $query = GameAccountLink::whereHost($host)
            ->whereTargetAccountId($accountID)
            ->whereTargetUserId($userID);

        if ($query->exists()) {
            return $request->failed('该账号已被绑定');
        }

        GameAccountLink::create([
            'host' => $host,
            'account' => Auth::id(),
            'target_account_id' => $accountID,
            'target_user_id' => $userID,
            'target_name' => $data['remote_name']
        ]);

        return $request->success();
    }

    /**
     * @param ApiRequest $request
     * @return array
     */
    public function getLinkedAccounts(ApiRequest $request): array
    {
        $accountID = Auth::id();
        $links = GameAccountLink::whereAccount($accountID)->get(['id', 'host', 'target_name', 'created_at']);
        return $request->success($links);
    }


    /**
     * @param WebToolsApiAccountUnlinkRequest $request
     * @return array
     */
    public function unlinkLinkedAccount(WebToolsApiAccountUnlinkRequest $request): ?array
    {
        try {
            $request->link->delete();
            return $request->success();
        } catch (Exception $e) {
            return $request->failed();
        }
    }

    /**
     * @param WebToolsApiReuploadLevelRequest $request
     * @return array
     */
    public function reuploadLevel(WebToolsApiReuploadLevelRequest $request): array
    {
        $storages = config('game.storage.levels');
        $storageManager = new StorageManager($storages);

        $data = $request->validated();
        switch ($data['server']) {
            case 'official':
                $url = 'http://dl.geometrydashchinese.com/downloadGJLevel22.php';
                break;
            case 'custom':
                $url = $data['custom_server_url'];
                break;
            default:
                return $request->failed('未知服务器类型');
        }

        /** @var GameAccount $account */
        $account = Auth::user();
        $accountID = Auth::id();
        if (empty($account->user->id)) {
            return $request->failed('用户ID获取失败');
        }

        $host = parse_url($url, PHP_URL_HOST);
        $url = $host === 'www.boomlings.com' ? 'http://dl.geometrydashchinese.com/downloadGJLevel22.php' : $url;

        $req = Http::asForm()
            ->post($url, [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'levelID' => $data['levelID'],
                'secret' => 'Wmfd2893gb7',
                'inc' => 1,
                'extras' => 0
            ]);

        $response = $req->body();
        if (empty($response) || $response === '-1') {
            return $request->failed('关卡不存在 或者 Robtop 不喜欢你');
        }

        $levelString = explode('#', $response)[0];
        $levelObject = GDObject::split($levelString, ':');
        if (empty($levelObject[4])) {
            return $request->failed('错误: levelString 为空');
        }

        $linkExist = GameAccountLink::whereHost($host)->whereAccount($accountID)->whereTargetUserId($levelObject[6])->exists();
        if (!$linkExist) {
            return $request->failed('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
        }

        $level = GameLevel::firstOrCreate([
            'original' => $levelObject[1]
        ], [
            'user' => $account->user->id,
            'game_version' => $levelObject[13],
            'name' => $levelObject[2],
            'desc' => $levelObject[3],
            'version' => $levelObject[5],
            'length' => $levelObject[15],
            'audio_track' => $levelObject[12],
            'song' => $levelObject[35],
            'password' => Hash::decode($levelObject[27], Hash::$keys['level_password']),
            'two_player' => $levelObject[31],
            'objects' => $levelObject[45],
            'coins' => $levelObject[37],
            'requested_stars' => $levelObject[39],
            'ldm' => $levelObject[40] ?: false,
            'extra_string' => $levelObject[36] ?: 'Unknown',
            'level_info' => 'Unknown'
        ]);

        if (!$level->save()) {
            return $request->failed();
        }

        $storageManager->put(sha1($level->id) . '.dat', $levelObject[4]);
        return $request->success(['id' => $level->id]);
    }

    public function levelToGd(WebToolsApiLevelToGdRequest $request)
    {
        $data = $request->validated();

        switch ($data['server']) {
            case 'official':
                $url = 'http://dl.geometrydashchinese.com/uploadGJLevel21.php';
                break;
            case 'custom':
                $url = $data['custom_server_url'];
                break;
            default:
                return $request->failed('未知服务器类型');
        }

        $storages = config('game.storage.levels');
        $storageManager = new StorageManager($storages);

        try {
            $level = GameLevel::findOrFail($data['levelID']);
        } catch (ModelNotFoundException $e) {
            return $request->failed('关卡不存在（或未找到）');
        }

        $host = parse_url($url, PHP_URL_HOST);
        $url = $host === 'www.boomlings.com' ? 'http://dl.geometrydashchinese.com/uploadGJLevel21.php' : $url;

        $link = GameAccountLink::whereHost($host)->whereId($data['linkID'])->first();
        if (!$link->exists()) {
            return $request->failed('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
        }

        $levelString = $storageManager->get(sha1($level->id) . '.dat');
        if (empty($levelString)) {
            return $request->failed('错误: levelString 为空');
        }

        switch ($data['songType']) {
            case 'original':
                $audio_track = $level->audio_track;
                $song = $level->song;
                break;
            case 'official':
                $audio_track = $data['songID'];
                $song = 0;
                break;
            case 'newgrounds':
                $audio_track = 0;
                $song = $data['songID'];
                break;
            default:
                return $request->failed('未知歌曲类型');
        }

        $req = Http::asForm()
            ->post($url, [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $link->target_account_id,
                'gjp' => Hash::encode($data['password'], Hash::$keys['account_password']),
                'userName' => $link->target_name,
                'levelID' => 0,
                'levelName' => $level->name,
                'levelDesc' => $level->desc,
                'levelVersion' => $level->version,
                'levelLength' => $level->length,
                'audioTrack' => $audio_track,
                'auto' => $level->auto,
                'password' => Hash::encode($level->password, Hash::$keys['level_password']),
                'original' => 0,
                'twoPlayer' => $level->two_player,
                'songID' => $song,
                'objects' => $level->objects,
                'coins' => $level->coins,
                'requestedStars' => $level->requested_stars,
                'unlisted' => 0,
                'wt' => 0,
                'wt2' => 3,
                'extraString' => $level->extra_string,
                'seed' => 'v2R5VPi53f',
                'seed2' => Hash::generateSeed2ForUploadLevel($levelString, true),
                'levelString' => $levelString,
                'levelInfo' => $level->level_info,
                'secret' => 'Wmfd2893gb7'
            ]);

        $response = $req->body();
        if ($response === '-1') {
            return $request->failed('上传失败');
        }

        return $request->success(['id' => $response]);
    }
}
