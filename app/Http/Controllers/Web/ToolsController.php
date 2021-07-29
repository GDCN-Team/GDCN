<?php

namespace App\Http\Controllers\Web;

use App\Enums\Game\Log\Types;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Traits\AuthTrait;
use App\Http\Controllers\Web\Traits\ResponseTrait;
use App\Http\Controllers\Web\Traits\ServerTrait;
use App\Http\Requests\Web\Api\Tools\Account\AccountLinkRequest;
use App\Http\Requests\Web\Api\Tools\Level\TransInRequest;
use App\Http\Requests\Web\Api\Tools\Level\TransOutRequest;
use App\Http\Requests\Web\Api\Tools\Song\EditRequest;
use App\Http\Requests\Web\Api\Tools\Song\LinkUploadRequest;
use App\Http\Requests\Web\Api\Tools\Song\NeteaseUploadRequest;
use App\Models\Game\Account\Link;
use App\Models\Game\CustomSong;
use App\Models\Game\Level;
use App\Models\Game\Log;
use App\Services\Game\LevelService;
use GDCN\GDObject;
use GDCN\Hash\Hasher;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ToolsController extends Controller
{
    use AuthTrait;
    use ServerTrait;
    use ResponseTrait;

    /**
     * ToolsController constructor.
     */
    public function __construct()
    {
        $this->mustVerifyEmail = true;
    }

    /**
     * @return array
     */
    public function getServers(): array
    {
        return $this->response(true, null, $this->servers);
    }

    /**
     * @param AccountLinkRequest $request
     * @return array
     */
    public function linkAccount(AccountLinkRequest $request): array
    {
        $data = $request->validated();

        $response = $this->requestServer($data['server'], 'accounts/loginGJAccount.php', [
            'userName' => $data['name'],
            'password' => $data['password'],
            'udid' => Str::uuid()->toString(),
            'secret' => 'Wmfv3899gc9'
        ]);

        if ($response === null || $response < 0) {
            return $this->response(false, '登录失败');
        }

        $account = $this->getAccount();
        [$accountID, $userID] = explode(',', $response);

        $link = Link::firstOrNew([
            'server' => $data['server'],
            'target_user_id' => $userID
        ], [
            'account' => $account->id,
            'target_name' => $data['name'],
            'target_account_id' => $accountID
        ]);

        if ($link->exists()) {
            return $this->response(false, '账号 ' . $data['name'] . '[' . $accountID . ',' . $userID . '] 已被绑定');
        }

        $link->save();
        return $this->response(true);
    }

    /**
     * @param int $id
     * @return array
     */
    public function unlinkAccount(int $id): array
    {
        $account = $this->getAccount();
        $link = Link::find($id);
        if (!$link) {
            return $this->response(false, '链接不存在(或未找到)');
        }

        if ($account->can('unlink', $link)) {
            return $this->response(
                $link->delete()
            );
        }

        return $this->response(false, '无权解绑');
    }

    /**
     * @return array
     */
    public function linkedAccounts(): array
    {
        $account = $this->getAccount();
        return $this->response(true, null, $account->links);
    }

    /**
     * @param TransInRequest $request
     * @return array
     */
    public function levelTransIn(TransInRequest $request): array
    {
        $data = $request->validated();

        $level = $this->requestServer($data['server'], 'downloadGJLevel22.php', [
            'levelID' => $data['id'],
            'secret' => 'Wmfd2893gb7'
        ]);

        if ($level === null || $level < 0) {
            return $this->response(false, '关卡获取失败');
        }

        $levelObject = explode('#', $level)[0];
        $levelObject = GDObject::split($levelObject, ':');

        $levelCreatorUserID = $levelObject[6];
        $link = Link::whereTargetUserId($levelCreatorUserID)
            ->with('owner')
            ->first();

        if (!$link->exists()) {
            return $this->response(false, '账号链接未找到, 请链接账号');
        }

        $linkAccount = $link->owner;
        if (!$linkAccount->user) {
            return $this->response(false, '用户不存在(或未找到)');
        }

        $log = Log::firstOrNew([
            'type' => Types::REUPLOAD_LEVEL,
            'value' => "{$data['server']}:$levelObject[1]"
        ], [
            'user' => $linkAccount->id,
            'ip' => $request->ip()
        ]);

        if ($log->exists()) {
            return $this->response(false, '关卡 ' . $levelObject[2] . '[' . $levelObject[1] . '] 已经被搬运过了, 请勿重复搬运');
        }

        $log->save();
        $levelService = app(LevelService::class);
        $hasher = app(Hasher::class);

        $password = 0;
        if (!empty($levelObject[27])) {
            if (is_numeric($levelObject[27])) {
                $password = $levelObject[27];
            } else {
                $password = $hasher->decodeLevelPassword($levelObject[27]);
            }
        }

        $level = $levelService->upload(
            $linkAccount->user,
            0,
            $levelObject[13],
            $levelObject[2],
            $levelObject[3],
            $levelObject[5] ?? 1,
            $levelObject[15] ?? 0,
            $levelObject[12] ?? 0,
            $levelObject[35] ?? 0,
            false,
            $password,
            $levelObject[1],
            $levelObject[31] ?? false,
            $levelObject[45] ?? 0,
            $levelObject[37] ?? 0,
            $levelObject[39] ?? 0,
            false,
            $levelObject[40] ?? false,
            $levelObject[36] ?? 'Unknown',
            "trans_in:$log->id",
            $levelObject[4]
        );

        return $this->response(true, '上传成功! 关卡ID: ' . $level->id);
    }

    /**
     * @param TransOutRequest $request
     * @return array
     */
    public function levelTransOut(TransOutRequest $request): array
    {
        $data = $request->validated();
        $link = Link::find($data['link']);
        $level = Level::find($data['id']);

        $account = $this->getAccount();
        if (!$link->owner->is($account)) {
            return $this->response(false, '这个链接不属于你');
        }

        if (!optional($level->creator)->is($account->user)) {
            return $this->response(false, '这个关卡不属于你');
        }

        switch ($data['song_type']) {
            case 'audio_track':
                $level->audio_track = $data['song'];
                $level->song = 0;
                break;
            case 'newgrounds':
                $level->audio_track = 0;
                $level->song = $data['song'];
                break;
        }

        $hasher = app(Hasher::class);
        $levelService = app(LevelService::class);
        $levelString = $levelService->getLevelString($level->id);

        $levelID = $this->requestServer($link->server, 'uploadGJLevel21.php', [
            'gameVersion ' => $level->game_version,
            'accountID' => $link->target_account_id,
            'gjp' => $hasher->encodeGJP($data['password']),
            'userName' => $link->target_name,
            'levelID' => 0,
            'levelName' => $level->name,
            'levelDesc' => $level->desc,
            'levelVersion' => $level->version,
            'levelLength' => $level->length,
            'audio_track' => $level->audio_track,
            'auto' => $level->auto,
            'password' => $level->password,
            'original' => 0,
            'twoPlayer' => $level->two_player,
            'songID' => $level->song,
            'objects' => $level->objects,
            'coins' => $level->coins,
            'requestedStars' => $level->requested_stars,
            'unlisted' => $level->unlisted,
            'ldm' => $level->ldm,
            'levelString' => $levelString,
            'seed2' => $hasher->generateSeed2ForTransOutLevel($levelString),
            'secret' => 'Wmfd2893gb7'
        ]);

        if ($levelID === null || $levelID < 0) {
            return $this->response(false, "搬出失败");
        }

        return $this->response(true, "搬出成功! 关卡ID: $levelID");
    }

    /**
     * @param NeteaseUploadRequest $request
     * @return array
     */
    public function uploadNeteaseMusic(NeteaseUploadRequest $request): array
    {
        $data = $request->validated();

        $account = $this->getAccount();
        $songInfo = $this->getNeteaseMusicDetail($data['id'])['songs'][0];

        $hash_value = "netease:{$songInfo['id']}";
        $hash = sha1($hash_value);
        $song = CustomSong::whereHash($hash)->first();

        if (!empty($song)) {
            return $this->response(false, "歌曲 {$songInfo['name']} 已被上传过了, 歌曲ID: {$song->value('song_id')}");
        }

        if (empty($songInfo)) {
            return $this->response(false, '歌曲不存在(或未找到)');
        }

        $log = new Log();
        $log->type = Types::UPLOAD_SONG;
        $log->user = optional($account->user)->id ?? null;
        $log->value = $hash_value;
        $log->ip = Request::ip();
        $log->save();

        $artists = [];
        foreach ($songInfo['artists'] as $artist) {
            $artists[] = $artist['name'];
        }

        $song = new CustomSong();
        $song->song_id = $data['song_id'];
        $song->type = 'netease';
        $song->uploader = $account->id;
        $song->name = $songInfo['name'];
        $song->author_name = implode(' / ', $artists);
        $song->size = round($songInfo['lMusic']['size'] / 1024 / 1024, 2);
        $song->download_url = "https://music.163.com/song/media/outer/url?id={$songInfo['id']}.mp3";
        $song->hash = $hash;
        $song->disabled = false;
        $song->save();

        return $this->response(true);
    }

    /**
     * @param int $musicID
     * @return mixed
     */
    protected function getNeteaseMusicDetail(int $musicID): mixed
    {
        return Http::get("https://music.163.com/api/song/detail?ids=[$musicID]")->json();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getNeteaseMusicInfo(int $id): array
    {
        return $this->response(
            true,
            null,
            $this->getNeteaseMusicDetail($id)
        );
    }

    /**
     * @return array
     */
    public function getLatestSongID(): array
    {
        $offset = config('game.customSongIdOffset');
        $latestSong = CustomSong::query()
            ->orderByDesc('song_id')
            ->first();

        $id = !$latestSong || $latestSong->song_id < $offset ? $offset : $latestSong->song_id + 1;
        return $this->response(true, null, [
            'id' => $id
        ]);
    }

    /**
     * @return array
     */
    public function getSongList(): array
    {
        return $this->response(
            true,
            null,
            CustomSong::paginate(10)
        );
    }

    /**
     * @param int $id
     * @return array
     */
    public function deleteSong(int $id): array
    {
        $account = $this->getAccount();
        $song = CustomSong::find($id);
        if (!$song) {
            return $this->response(false, '歌曲不存在(或未找到)');
        }

        if ($account->can('delete', $song)) {
            return $this->response(
                $song->delete()
            );
        }

        return $this->response(false, '无权删除该歌曲');
    }

    /**
     * @param LinkUploadRequest $request
     * @return array
     */
    public function uploadLink(LinkUploadRequest $request): array
    {
        $data = $request->validated();

        $account = $this->getAccount();
        if (parse_url($data['url'])['host'] === 'music.163.com') {
            return $this->response(false, '检测到网易云音乐链接, 请使用 歌曲上传(网易云专版) 上传');
        }

        $hash_value = "link:{$data['url']}";
        $hash = sha1($hash_value);
        $song = CustomSong::whereHash($hash)->first();

        if (!empty($song)) {
            return $this->response(false, "该歌曲已被上传过了, 歌曲ID: {$song->value('song_id')}");
        }

        $request = Http::get($data['url']);
        if (trim(explode('/', $request->header('Content-Type'))[0]) !== 'audio') {
            return $this->response(false, '链接无效');
        }

        $log = new Log();
        $log->type = Types::UPLOAD_SONG;
        $log->user = optional($account->user)->id ?? null;
        $log->value = $hash_value;
        $log->ip = Request::ip();
        $log->save();

        $song = new CustomSong();
        $song->song_id = $data['song_id'];
        $song->type = 'link';
        $song->uploader = $account->id;
        $song->name = $data['name'];
        $song->author_name = $data['author_name'];
        $song->size = round(strlen($request->body()) / 1024 / 1024, 2);
        $song->download_url = $data['url'];
        $song->hash = $hash;
        $song->disabled = false;
        $song->save();

        return $this->response(true);
    }

    /**
     * @param EditRequest $request
     * @return array
     */
    public function editSong(EditRequest $request): array
    {
        $data = $request->validated();
        $account = $this->getAccount();
        $song = CustomSong::find($data['id']);

        if (!$account->can('edit', $song)) {
            return $this->response(false, '无权编辑该歌曲');
        }

        $song->song_id = $data['song_id'];
        if ($song->type !== 'netease') {
            $song->name = $data['name'];
            $song->author_name = $data['author_name'];
        }

        return $this->response(
            $song->save()
        );
    }
}
