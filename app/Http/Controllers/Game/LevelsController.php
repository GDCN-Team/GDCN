<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\Level\SearchType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\DailyGetRequest;
use App\Http\Requests\Game\Level\DeleteRequest;
use App\Http\Requests\Game\Level\DownloadRequest;
use App\Http\Requests\Game\Level\ReportRequest;
use App\Http\Requests\Game\Level\SearchRequest;
use App\Http\Requests\Game\Level\UpdateDescRequest;
use App\Http\Requests\Game\Level\UploadRequest;
use App\Services\Game\LevelService;

/**
 * Class LevelsController
 * @package App\Http\Controllers
 */
class LevelsController extends Controller
{
    public function __construct(
        public LevelService $service
    )
    {
    }

    /**
     * Upload Level
     *
     * @param UploadRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJLevel21
     */
    public function upload(UploadRequest $request): int
    {
        $data = $request->validated();
        $level = $this->service->upload(
            $request->getPlayer(),
            $data['levelID'],
            $data['gameVersion'],
            $data['levelName'],
            $data['levelDesc'],
            $data['levelVersion'],
            $data['levelLength'],
            $data['audioTrack'],
            $data['songID'],
            $data['auto'],
            $data['password'],
            $data['original'],
            $data['twoPlayer'],
            $data['objects'],
            $data['coins'],
            $data['requestedStars'],
            $data['unlisted'],
            $data['ldm'],
            $data['extraString'],
            $data['levelInfo'],
            $data['levelString']
        );

        return $level ? $level->id : ResponseCode::LEVEL_UPLOAD_FAILED;
    }

    /**
     * @param SearchRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJLevels21
     */
    public function search(SearchRequest $request): int|string
    {
        try {
            $data = $request->validated();
            return $this->service->search(
                SearchType::fromValue((int)$data['type']),
                $data['str'],
                $data['page'],
                $data['accountID'] ?? 0,
                $data['followed'] ?? null,
                $data['len'] ?? null,
                $data['star'] ?? null,
                $data['diff'] ?? null,
                $data['demonFilter'] ?? null,
                $data['unCompleted'] ?? false,
                $data['onlyCompleted'] ?? false,
                $data['completedLevels'] ?? null,
                $data['featured'] ?? false,
                $data['original'] ?? false,
                $data['epic'] ?? false,
                $data['song'] ?? null,
                $data['customSong'] ?? false,
                $data['noStar'] ?? false,
                $data['coins'] ?? false,
                $data['twoPlayer'] ?? false
            );
        } catch (InvalidArgumentException) {
            return ResponseCode::INVALID_REQUEST;
        } catch (NoItemException) {
            return ResponseCode::EMPTY_RESULT_STRING;
        }
    }

    /**
     * @param DownloadRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/downloadGJLevel22
     */
    public function download(DownloadRequest $request): int|string
    {
        $data = $request->validated();
        return $this->service->download($data['levelID']);
    }

    /**
     * @param DeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJLevelUser20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        return $this->service->delete(
            $request->getPlayer(),
            $data['levelID']
        ) ? ResponseCode::LEVEL_DELETE_SUCCESS : ResponseCode::LEVEL_DELETE_FAILED;
    }

    /**
     * @param DailyGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJDailyLevel
     */
    public function getDaily(DailyGetRequest $request): string
    {
        $data = $request->validated();
        if ($data['weekly']) {
            return $this->service->getWeekly();
        } else {
            return $this->service->getDaily();
        }
    }

    /**
     * @param ReportRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/reportGJLevel
     */
    public function report(ReportRequest $request): int
    {
        $data = $request->validated();
        return $this->service->report($data['levelID'])
            ? ResponseCode::LEVEL_REPORT_SUCCESS : ResponseCode::LEVEL_REPORT_FAILED;
    }

    /**
     * @param UpdateDescRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/updateGJDesc20
     */
    public function updateDesc(UpdateDescRequest $request): int
    {
        $data = $request->validated();
        return $this->service->updateDesc(
            $request->getPlayer(),
            $data['levelID'],
            $data['levelDesc']
        ) ? ResponseCode::LEVEL_UPDATE_DESC_SUCCESS : ResponseCode::LEVEL_UPDATE_DESC_FAILED;
    }
}
