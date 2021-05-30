<?php

namespace App\Services;

use App\Game\StorageManager;
use App\Models\GameAccount;
use App\Models\GameAccountLink;
use App\Models\GameLevel;
use GDCN\GDObject;
use GDCN\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class WebToolsLevelService
 * @package App\Services
 */
class WebToolsLevelService
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * WebToolsLevelService constructor.
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebNoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    public function transIn($host, $levelID)
    {
        $storages = config('game.storage.levels');
        $storageManager = new StorageManager($storages);

        /** @var GameAccount $account */
        $account = Auth::user();
        if (empty($account->user->id)) {
            $this->noticeService->sendErrorNotice('用户ID获取失败');
        } else {
            $request = Http::asForm()
                ->post("http://$host/downloadGJLevel22.php", [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => 0,
                    'levelID' => $levelID,
                    'secret' => 'Wmfd2893gb7',
                    'inc' => 1,
                    'extras' => 0
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

                    if ($host === 'www.boomlings.com/database') {
                        $query->orWhere('host', 'dl.geometrydashchinese.com');
                    }

                    if ($host === 'dl.geometrydashchinese.com') {
                        $query->orWhere('host', 'www.boomlings.com/database');
                    }

                    if (!$query->whereAccount($account->id)->whereTargetUserId($levelObject[6])->exists()) {
                        $this->noticeService->sendErrorNotice('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
                    } else {
                        $level = new GameLevel;
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

        $this->noticeService->loadNotices();
        return Inertia::render('Tools/Level/TransIn');
    }

    /**
     * @param $host
     * @param $levelID
     * @param $password
     * @return Response
     */
    public function transOut($host, $levelID, $password): Response
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
                $query = GameAccountLink::whereHost($host);

                if ($host === 'www.boomlings.com/database') {
                    $query->orWhere('host', 'dl.geometrydashchinese.com');
                }

                if ($host === 'dl.geometrydashchinese.com') {
                    $query->orWhere('host', 'www.boomlings.com/database');
                }

                $query = $query->whereAccount($account->id)->whereTargetUserId($account->user->id);
                $link = $query->first();

                if (!$link || !$query->exists()) {
                    $this->noticeService->sendErrorNotice('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
                } else {
                    $levelString = $storageManager->get(sha1($level->id) . '.dat');
                    if (empty($levelString)) {
                        $this->noticeService->sendErrorNotice('错误: levelString 为空');
                    } else {
                        $request = Http::asForm()
                            ->post("http://$host/uploadGJLevel21.php", [
                                'gameVersion' => 21,
                                'binaryVersion' => 35,
                                'gdw' => 0,
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
                                'wt' => 0,
                                'wt2' => 3,
                                'extraString' => $level->extra_string,
                                'seed' => 'v2R5VPi53f',
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

        $this->noticeService->loadNotices();
        return Inertia::render('Tools/Level/TransOut');
    }
}
