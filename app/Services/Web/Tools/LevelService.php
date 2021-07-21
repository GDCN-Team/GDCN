<?php

namespace App\Services\Web\Tools;

use App\Exceptions\Web\Tools\UnknownServerException;
use App\Game\Helpers;
use App\Models\Game\Account;
use App\Models\Game\Account\Link;
use App\Models\Game\Level;
use App\Presenter\Web\ToolsPresenter;
use App\Services\Web\NoticeService;
use GDCN\GDObject;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use function app;

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
     * @var ToolsPresenter
     */
    protected $presenter;

    /**
     * LevelService constructor.
     * @param ToolsPresenter $presenter
     * @param NoticeService $noticeService
     */
    public function __construct(ToolsPresenter $presenter, NoticeService $noticeService)
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
        /** @var Account $account */
        $account = Auth::user();
        if (empty($account->user->id)) {
            $this->noticeService->sendErrorNotice('用户ID获取失败');
        } else {
            try {
                $host = app(Helpers::class)->getServerHostFromAlias($serverAlias);
            } catch (UnknownServerException) {
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
                    $query = Link::whereHost($host);

                    if (!$query->whereAccount($account->id)->whereTargetUserId($levelObject[6])->exists()) {
                        $this->noticeService->sendErrorNotice('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
                    } else {
                        $level = new Level();
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
                            Storage::disk('oss')->put("gdcn/levels/$level->id.dat", $levelObject[4]);
                            $this->noticeService->sendSuccessNotice('搬运成功!', "关卡ID: $level->id");
                        }
                    }
                }
            }
        }

        return $this->presenter->levelTransIn();
    }

    /**
     * @param $serverAlias
     * @param $levelID
     * @param $songType
     * @param null $songID
     * @param $password
     * @return Response
     * @throws UnknownServerException
     */
    public function transOut($serverAlias, $levelID, $songType, $songID, $password): Response
    {
        try {
            /** @var Account $account */
            $account = Auth::user();

            if (empty($account->user->id)) {
                $this->noticeService->sendErrorNotice('用户ID获取失败');
            } else {
                $level = Level::query()->findOrFail($levelID);
                $host = app(Helpers::class)->getServerHostFromAlias($serverAlias);
                $link = Link::whereHost($host)->whereAccount($account->id)->first();
                if (!$link) {
                    $this->noticeService->sendErrorNotice('错误: 未检测到账号链接信息，请链接关卡Creator的账号');
                } else {
                    try {
                        $levelString = Storage::disk('oss')->get("gdcn/levels/$level->id.dat");

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
                    } catch (FileNotFoundException) {
                        $this->noticeService->sendErrorNotice('错误: levelString 丢失');
                    }
                }
            }
        } catch (ModelNotFoundException) {
            $this->noticeService->sendErrorNotice('关卡不存在（或未找到）');
        }

        return $this->presenter->levelTransOut();
    }
}
