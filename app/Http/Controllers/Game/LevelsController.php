<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\Level\SearchType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Level\DailyGetRequest;
use App\Http\Requests\Game\Level\DeleteRequest;
use App\Http\Requests\Game\Level\DownloadRequest;
use App\Http\Requests\Game\Level\ReportRequest;
use App\Http\Requests\Game\Level\SearchRequest;
use App\Http\Requests\Game\Level\UpdateDescRequest;
use App\Http\Requests\Game\Level\UploadRequest;
use App\Services\Game\LevelService;
use Illuminate\Support\Arr;

class LevelsController extends Controller
{
    public function __construct(
        public LevelService $service
    )
    {
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/uploadGJLevel21
     */
    public function upload(UploadRequest $request): int
    {
        $data = $request->validated();
        if (!$level = $this->service->upload(Arr::getAny($data, ['accountID', 'udid']), $data['levelID'], $data['gameVersion'], $data['levelName'], $data['levelDesc'], $data['levelVersion'], $data['levelLength'], $data['audioTrack'], $data['songID'], $data['auto'], $data['password'], $data['original'], $data['twoPlayer'], $data['objects'], $data['coins'], $data['requestedStars'], $data['unlisted'], $data['ldm'], $data['extraString'], $data['levelInfo'], $data['levelString'])) {
            return ResponseCode::LEVEL_UPLOAD_FAILED;
        }

        return $level->id;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJLevels21
     * @throws InvalidArgumentException
     */
    public function search(SearchRequest $request): string
    {
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
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/downloadGJLevel22
     */
    public function download(DownloadRequest $request): string
    {
        $data = $request->validated();
        return $this->service->download($data['levelID']);
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/deleteGJLevelUser20
     */
    public function delete(DeleteRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->delete(Arr::getAny($data, ['accountID', 'udid']), $data['levelID'])) {
            return ResponseCode::LEVEL_DELETE_FAILED;
        }

        return ResponseCode::LEVEL_DELETE_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/getGJDailyLevel
     */
    public function getDaily(DailyGetRequest $request): string
    {
        $data = $request->validated();
        if (!empty($data['weekly'])) {
            return $this->service->getWeekly();
        }

        return $this->service->getDaily();
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/reportGJLevel
     */
    public function report(ReportRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->report($data['levelID'])) {
            return ResponseCode::LEVEL_REPORT_FAILED;
        }

        return ResponseCode::LEVEL_REPORT_SUCCESS;
    }

    /**
     * @link http://docs.gdprogra.me/#/endpoints/updateGJDesc20
     */
    public function updateDesc(UpdateDescRequest $request): int
    {
        $data = $request->validated();
        if (!$this->service->updateDesc(Arr::getAny($data, ['accountID', 'udid']), $data['levelID'], $data['levelDesc'])) {
            return ResponseCode::LEVEL_UPDATE_DESC_FAILED;
        }

        return ResponseCode::LEVEL_UPDATE_DESC_SUCCESS;
    }
}
