<?php

namespace App\Services\Web\Tools;

use App\Exceptions\Web\Tools\LevelTransInException;
use App\Exceptions\Web\Tools\LevelTransOutException;
use App\Models\Game\Account;
use App\Models\Game\Account\Link;
use App\Models\Game\Level;
use App\Services\Game\LevelService as GameLevelService;
use GDCN\GDObject;
use GDCN\Hash\Components\GJP as GJPComponent;
use GDCN\Hash\Components\LevelPassword as LevelPasswordComponent;
use GDCN\Hash\Components\LevelString as LevelStringComponent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LevelService
{
    /**
     * @var array
     */
    public array $servers;

    /**
     * @param array $servers
     * @return LevelService
     */
    public function setServers(array $servers): LevelService
    {
        $this->servers = $servers;
        return $this;
    }

    /**
     * @param string $server
     * @param int $levelID
     * @return bool|int
     * @throws LevelTransInException
     */
    public function transIn(string $server, int $levelID): bool|int
    {
        if (!$serverProperty = Arr::get($this->servers, $server)) {
            return false;
        }

        $serverUrl = "{$serverProperty['endpoint']}/downloadGJLevel22.php";
        $response = Http::post($serverUrl, [
            'levelID' => $levelID,
            'secret' => 'Wmfd2893gb7'
        ])->body();

        if ($response <= 0) {
            if ($response === '-1') {
                throw new LevelTransInException("关卡下载失败, 错误码: $response");
            }

            throw new LevelTransInException("Robtop 不喜欢你并返回了错误码: $response");
        }

        $levelObject = GDObject::split($response, ':');
        if (empty($levelObject[4])) {
            throw new LevelTransInException("关卡下载失败, 远程 LevelString 字段为空");
        }

        $levelUserID = $levelObject[6];
        if (empty($levelUserID) || !$this->checkLinkByTargetUserID($levelUserID)) {
            throw new LevelTransInException("与关卡作者的账号链接不存在(或未找到)");
        }

        $service = app(GameLevelService::class);

        $level = $service->upload(
            Auth::user(),
            $levelID,
            $levelObject[13],
            $levelObject[2],
            $levelObject[3],
            $levelObject[5] ?? 1,
            $levelObject[15] ?? 0,
            $levelObject[12] ?? 0,
            $levelObject[35] ?? 0,
            false,
            app(LevelPasswordComponent::class)->decode($levelObject[27]),
            $levelObject[1],
            $levelObject[31] ?? false,
            $levelObject[45] ?? 0,
            $levelObject[37] ?? 0,
            $levelObject[39] ?? 0,
            false,
            $levelObject[40] ?? false,
            $levelObject[36] ?? 'Unknown',
            'Unknown',
            $levelObject[4]
        );

        return $level->id;
    }

    /**
     * @param int $userID
     * @return bool
     */
    protected function checkLinkByTargetUserID(int $userID): bool
    {
        return Link::where([
            'account' => Auth::id(),
            'target_user_id' => $userID
        ])->exists();
    }

    /**
     * @param string $server
     * @param int $levelID
     * @param int $linkID
     * @param string $password
     * @param string|null $level_name
     * @param string|null $level_desc
     * @param string|null $level_song_type
     * @param int|null $level_song_id
     * @param bool|null $level_unlisted
     * @param int|null $level_password
     * @return bool|string
     * @throws LevelTransOutException
     */
    public function transOut(string $server, int $levelID, int $linkID, string $password, ?string $level_name, ?string $level_desc, ?string $level_song_type, ?int $level_song_id, ?bool $level_unlisted, ?int $level_password): bool|string
    {
        if (!$serverProperty = Arr::get($this->servers, $server)) {
            return false;
        }

        if (!$level = Level::find($levelID)) {
            throw new LevelTransOutException('关卡不存在(或未找到)');
        }

        /** @var Account $account */
        $account = Auth::user();

        if (!$level->getRelationValue('user')->is($account->user)) {
            throw new LevelTransOutException('这个关卡不属于你');
        }

        if (!$link = Link::find($linkID)) {
            throw new LevelTransOutException('账号不存在(或未找到)');
        }

        if (!$link->getRelationValue('account')->is($account)) {
            throw new LevelTransOutException('这个链接不属于你');
        }

        if ($serverProperty['endpoint'] !== $link->server) {
            throw new LevelTransOutException('选择的服务器与链接不匹配');
        }

        $service = app(GameLevelService::class);
        $serverUrl = "{$serverProperty['endpoint']}/uploadGJLevel21.php";
        $response = Http::post($serverUrl, [
            'gameVersion' => $level->game_version,
            'accountID' => $link->target_account_id,
            'gjp' => app(GJPComponent::class)->decode($password),
            'userName' => $link->target_name,
            'levelID' => 0,
            'levelName' => $level_name ?? $level->name,
            'levelDesc' => $level_desc ?? $level->desc,
            'levelVersion' => $level->version,
            'levelLength' => $level->length,
            'audioTrack' => $level_song_type === 'audio_track' ? $level_song_id : $level->audio_track,
            'auto' => $level->auto,
            'password' => app(LevelPasswordComponent::class)->encode($level_password ?? $level->password),
            'original' => 0,
            'twoPlayer' => $level->two_player,
            'songID' => $level_song_type === 'newgrounds' ? $level_song_id : $level->song,
            'objects' => $level->objects,
            'coins' => $level->coins,
            'requestedStars' => $level->requested_stars,
            'unlisted' => $level_unlisted ?? $level->unlisted,
            'ldm' => $level->ldm,
            'levelString' => $levelString = $service->getLevelString($level),
            'seed2' => app(LevelStringComponent::class)->generateHash($levelString),
            'secret' => 'Wmfd2893gb7'
        ])->body();

        if ($response <= 0) {
            if ($response === '-1') {
                throw new LevelTransOutException("关卡上传失败, 错误码: $response");
            }

            throw new LevelTransOutException("Robtop 不喜欢你并返回了错误码: $response");
        }

        return $response;
    }
}
