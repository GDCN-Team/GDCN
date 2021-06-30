<?php

namespace App\Services\Web\Tools;

use App\Exceptions\Web\Tools\UnknownServerException;
use App\Game\Helpers;
use App\Game\StorageManager;
use App\Models\GameAccount;
use App\Models\GameAccountLink;
use App\Models\GameLevel;
use App\Presenter\WebToolsPresenter;
use App\Services\Web\NoticeService;
use GDCN\GDObject;
use GDCN\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Response;
use function app;
use function config;

/**
 * Class LevelService
 * @package App\Services
 */
class LevelService
{
    /**
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var WebToolsPresenter
     */
    protected $presenter;

    /**
     * LevelService constructor.
     * @param WebToolsPresenter $presenter
     * @param NoticeService $noticeService
     */
    public function __construct(WebToolsPresenter $presenter, NoticeService $noticeService)
    {
        $this->presenter = $presenter;
        $this->noticeService = $noticeService;
    }

    /**
     * @param $serverAlias
     * @param $levelID
     * @return Response
     */
    public function transIn($serverAlias, $levelID): Response
    {
        $storages = config('game.storage.levels');
        $storageManager = new StorageManager($storages);

        /** @var GameAccount $account */
        $account = Auth::user();
        if (empty($account->user->id)) {
            $this->noticeService->sendErrorNotice('用户ID获取失败');
        } else {
            try {
                $host = app(Helpers::class)->getServerHostFromAlias($serverAlias);
            } catch (UnknownServerException $e) {
                $this->noticeService->sendErrorNotice('未知服务器');
                return $this->presenter->levelTransIn();
            }

            $request = Http::asForm()
                ->post("http://$host/downloadGJLevel22.php", [
                    'levelID' => $levelID,
                    'secret' => 'Wmfd2893gb7'
                ]);

            $response = $request->body();
            if (empty($response) || $response === '-1') {
                $this->noticeService->sendErrorNotice('关卡不存在 或者 Robtop 不喜欢你');
            } else {
                $levelString = explode('#', $response)[0];
                $levelObject = GDObject::split($levelString, ':');

                if (empty($levelObject[4])) {
                    $this->noticeService->sendErrorNotice('错误: levelString 为空');
                } else {
                    $query = GameAccountLink::whereHost($host);

                    if (!$query->whereAccount($account->id)->whereTargetUserId($levelObject[6])->exists()) {
                        $this->noticeService->sendErrorNotice('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
                    } else {
                        $level = new GameLevel();
                        $level->original = $levelObject[1];
                        $level->user = $account->user->id;
                        $level->game_version = $levelObject[13];
                        $level->name = $levelObject[2];
                        $level->desc = $levelObject[3];
                        $level->version = $levelObject[5];
                        $level->length = $levelObject[15];
                        $level->audio_track = $levelObject[12];
                        $level->song = $levelObject[35];
                        $level->password = !is_numeric($levelObject[27]) ? Hash::decode($levelObject[27], Hash::$keys['level_password']) : $levelObject[27];
                        $level->two_player = $levelObject[31];
                        $level->objects = $levelObject[45];
                        $level->coins = $levelObject[37];
                        $level->requested_stars = $levelObject[39];
                        $level->ldm = $levelObject[40] ?: false;
                        $level->extra_string = $levelObject[36] ?: 'Unknown';
                        $level->level_info = 'Unknown';

                        if (!$level->save()) {
                            $this->noticeService->sendErrorNotice('未知错误');
                        } else {
                            $storageManager->put(sha1($level->id) . ' . dat', $levelObject[4]);
                            $this->noticeService->sendSuccessNotice('搬运成功!', "关卡ID: $level->id");
                        }
                    }
                }
            }
        }

        return $this->presenter->levelTransIn();
    }

    /**
     * @param $host
     * @param $levelID
     * @param $songType
     * @param null $songID
     * @param $password
     * @return Response
     */
    public function transOut($serverAlias, $levelID, $songType, $songID, $password): Response
    {
        $storages = config('game.storage.levels');
        $storageManager = new StorageManager($storages);

        try {
            /** @var GameAccount $account */
            $account = Auth::user();

            if (empty($account->user->id)) {
                $this->noticeService->sendErrorNotice('用户ID获取失败');
            } else {
                $level = GameLevel::query()->findOrFail($levelID);
                $host = app(Helpers::class)->getServerHostFromAlias($serverAlias);
                $link = GameAccountLink::whereHost($host)->whereAccount($account->id)->first();
                if (!$link) {
                    $this->noticeService->sendErrorNotice('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
                } else {
                    $levelString = $storageManager->get(sha1($level->id) . '.dat');
                    if (empty($levelString)) {
                        $this->noticeService->sendErrorNotice('错误: levelString 为空');
                    } else {
                        switch ($songType) {
                            case 'audioTrack':
                                $level->audio_track = $songID;
                                $level->song = 0;
                                break;
                            case 'customSong':
                                $level->audio_track = 0;
                                $level->song = $songID;
                                break;
                        }

                        $request = Http::asForm()
                            ->post("http://$host/uploadGJLevel21.php", [
                                'gameVersion' => 21,
                                'accountID' => $link->target_account_id,
                                'gjp' => Hash::encode($password, Hash::$keys['account_password']),
                                'userName' => $link->target_name,
                                'levelID' => 0,
                                'levelName' => $level->name,
                                'levelDesc' => $level->desc,
                                'levelVersion' => $level->version,
                                'levelLength' => $level->length,
                                'audioTrack' => $level->audio_track,
                                'auto' => $level->auto,
                                'password' => Hash::encode($level->password, Hash::$keys['level_password']),
                                'original' => 0,
                                'twoPlayer' => $level->two_player,
                                'songID' => $level->song,
                                'objects' => $level->objects,
                                'coins' => $level->coins,
                                'requestedStars' => $level->requested_stars,
                                'unlisted' => 0,
                                'ldm' => $level->ldm,
                                'seed2' => Hash::generateSeed2ForUploadLevel($levelString, true),
                                'levelString' => $levelString,
                                'levelInfo' => $level->level_info,
                                'secret' => 'Wmfd2893gb7'
                            ]);

                        $response = $request->body();
                        if ($response === '-1') {
                            $this->noticeService->sendErrorNotice('上传失败');
                        } else {
                            $this->noticeService->sendSuccessNotice('上传成功!', "关卡ID: $response");
                        }
                    }
                }
            }
        } catch (ModelNotFoundException $e) {
            $this->noticeService->sendErrorNotice('关卡不存在（或未找到）');
        }

        return $this->presenter->levelTransOut();
    }
}
