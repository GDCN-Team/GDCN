<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\LevelSearchSpecialDiffFilter;
use App\Enums\Game\LevelSearchType;
use App\Enums\Game\LogType;
use App\Enums\Game\ResponseCode;
use App\Exceptions\GameAuthenticationException;
use App\Exceptions\GameUserNotFoundException as GameUserNotFoundExceptionAlias;
use App\Game\Helpers;
use App\Game\StorageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameDailyLevelGetRequest;
use App\Http\Requests\GameLevelDeleteRequest;
use App\Http\Requests\GameLevelDownloadRequest;
use App\Http\Requests\GameLevelReportRequest;
use App\Http\Requests\GameLevelSearchRequest;
use App\Http\Requests\GameLevelUpdateDescRequest;
use App\Http\Requests\GameLevelUploadRequest;
use App\Models\GameCustomSong;
use App\Models\GameDailyLevel;
use App\Models\GameLevel;
use App\Models\GameLevelReport;
use App\Models\GameLog;
use App\Models\GameSong;
use App\Models\GameUser;
use App\Models\GameWeeklyLevel;
use Exception;
use GDCN\ChkValidationException;
use GDCN\GDObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * Class LevelsController
 * @package App\Http\Controllers
 */
class LevelsController extends Controller
{
    /**
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * LevelsController constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    /**
     * Upload Level
     *
     * @param GameLevelUploadRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJLevel21
     */
    public function upload(GameLevelUploadRequest $request): int
    {
        try {
            $data = $request->validated();
            $user = $request->getGameUser();

            $level = GameLevel::whereId($data['levelID'])->firstOrNew();
            if ($level->exists() && $user->cannot('update', $level)) {
                $level = new GameLevel();
            }

            $level = $level->fill([
                'user' => $user->id,
                'game_version' => $data['gameVersion'],
                'name' => $data['levelName'],
                'desc' => $data['levelDesc'],
                'version' => $data['levelVersion'],
                'length' => $data['levelLength'],
                'audio_track' => $data['audioTrack'],
                'song' => $data['songID'],
                'auto' => $data['auto'],
                'password' => $data['password'],
                'original' => $data['original'],
                'two_player' => $data['twoPlayer'],
                'objects' => $data['objects'],
                'coins' => $data['coins'],
                'requested_stars' => $data['requestedStars'],
                'unlisted' => $data['unlisted'],
                'ldm' => $data['ldm'],
                'extra_string' => $data['extraString'],
                'level_info' => $data['levelInfo']
            ]);

            if (!$level->save()) {
                return ResponseCode::LEVEL_UPLOAD_FAILED;
            }

            $this->storageManager->put(sha1($level->id) . '.dat', $data['levelString']);
            return $level->id ?? ResponseCode::LEVEL_UPLOAD_FAILED;
        } catch (GameUserNotFoundExceptionAlias $e) {
            return ResponseCode::USER_NOT_FOUND;
        }
    }

    /**
     * @param GameLevelSearchRequest $request
     * @param Helpers $helper
     * @param HashesController $hash
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJLevels21
     */
    public function search(GameLevelSearchRequest $request, Helpers $helper, HashesController $hash)
    {
        try {
            $data = $request->validated();
            $query = GameLevel::query();

            // Advanced Options
            if (!empty($data['uncompleted']) && !empty($data['completedLevels'])) {
                $completedLevels = explode(',', strtr($data['completedLevels'], ['(' => null, ')' => null]));
                $query->whereNotIn('id', $completedLevels);
            }

            if (!empty($data['onlyCompleted']) && !empty($data['completedLevels'])) {
                $completedLevels = explode(',', strtr($data['completedLevels'], ['(' => null, ')' => null]));
                $query->whereIn('id', $completedLevels);
            }

            if (!empty($data['featured'])) {
                $data['type'] = LevelSearchType::FEATURED;
            }

            if (!empty($data['original'])) {
                $query->where('original', '!=', 0);
            }

            if (!empty($data['epic'])) {
                $data['type'] = LevelSearchType::HALL_OF_FAME;
            }

            if (!empty($data['song'])) {
                $query->where(!empty($data['customSong']) ? 'song' : 'audio_track', $data['song']);
            }

            if (!empty($data['noStar'])) {
                $query->whereDoesntHave('rating', function (Builder $query) {
                    $query->where('stars', '>', 0);
                });
            }

            if (!empty($data['coins'])) {
                $query->where('coins', '>', 0);
            }

            if (!empty($data['twoPlayer'])) {
                $query->where('two_player', true);
            }

            // Filters
            if (!empty($data['len']) && $data['len'] !== '-') {
                $query->whereIn('length', explode(',', $data['len']));
            }

            if (!empty($data['star'])) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('stars', '>', 0);
                });
            }

            if (!empty($data['diff']) && $data['diff'] !== '-') {
                switch ($data['diff']) {
                    case LevelSearchSpecialDiffFilter::NA:
                        $query->whereDoesntHave('rating', function (Builder $query) {
                            $query->where('difficulty', '!=', 0);
                            $query->where('stars', '!=', 0);
                        });
                        break;
                    case LevelSearchSpecialDiffFilter::DEMON:
                        $query->whereHas('rating', function (Builder $query) use ($helper, $data) {
                            $query->where('demon', true);

                            if (!empty($data['demonFilter'])) {
                                $diff = $helper->guessDemonDifficultyFromRating($data['demonFilter']);
                                $query->where('demon_difficulty', $diff);
                            }
                        });
                        break;
                    case LevelSearchSpecialDiffFilter::AUTO:
                        $query->whereHas('rating', function (Builder $query) {
                            $query->where('auto', true);
                        });
                        break;
                    default:
                        $difficulties = explode(',', strtr($data['diff'], [',' => '0,']) . '0');
                        $query->whereHas('rating', function (Builder $query) use ($difficulties) {
                            $query->where('auto', false);
                            $query->where('demon', false);
                            $query->whereIn('difficulty', $difficulties);
                        });
                        break;
                }
            }

            switch ($data['type']) {
                case LevelSearchType::SEARCH:
                    is_numeric($data['str']) ? $query->find($data['str']) : $query->where('name', 'LIKE', '%' . $data['str'] . '%');
                    break;
                case LevelSearchType::MOST_DOWNLOADED:
                    $query->orderByDesc('downloads');
                    break;
                case LevelSearchType::MOST_LIKED:
                    $query->orderByDesc('likes');
                    break;
                case LevelSearchType::TRENDING:
                    $query->where('created_at', '>', Carbon::rawParse('last week'));
                    $query->orderByDesc('likes');
                    break;
                case LevelSearchType::RECENT:
                    $query->orderByDesc('created_at');
                    break;
                case LevelSearchType::USER:
                    $query->where('user', $data['str']);
                    break;
                case LevelSearchType::FEATURED:
                    $query->whereHas('rating', function (Builder $query) {
                        $query->where('featured_score', '>', 0);
                    });
                    break;
                case LevelSearchType::MAGIC:
                    $query->where('objects', '>', 9999);
                    break;
                case LevelSearchType::PACK:
                    $query->whereIn('id', $data['str']);
                    break;
                case LevelSearchType::AWARDED:
                    $query->whereHas('rating', function (Builder $query) {
                        $query->where('stars', '>', 0);
                    });
                    break;
                case LevelSearchType::FOLLOWED:
                    $query->whereIn('user', explode(',', $data['followed']));
                    break;
                case LevelSearchType::FRIENDS:
                    try {
                        $request->auth();
                    } catch (GameAuthenticationException $e) {
                        return ResponseCode::LOGIN_FAILED;
                    }

                    $query->whereHas('creator', function (Builder $query) use ($request) {
                        $query->whereIn('uuid', $request->user()->friends->pluck('id'));
                    });
                    break;
                case LevelSearchType::HALL_OF_FAME:
                    $query->whereHas('rating', function (Builder $query) {
                        $query->where('epic', true);
                    });
                    break;
                default:
                    return ResponseCode::INVALID_REQUEST;
            }

            $count = $query->count();
            $page = $data['page'];

            $levels = $query->forPage(++$page, $helper->perPage)->get();
            $result = $levels->map(function (GameLevel $level) {
                return GDObject::merge([
                    1 => $level->id,
                    2 => $level->name,
                    3 => $level->desc,
                    5 => $level->version,
                    6 => $level->user,
                    8 => ($level->rating->difficulty ?? 0) > 0 ? 10 : 0,
                    9 => $level->rating->difficulty ?? 0,
                    10 => $level->downloads,
                    12 => $level->audio_track,
                    13 => $level->game_version,
                    14 => $level->likes,
                    15 => $level->length,
                    17 => $level->rating->demon ?? 0,
                    18 => $level->rating->stars ?? 0,
                    19 => $level->rating->featured_score ?? 0,
                    25 => $level->rating->auto ?? 0,
                    30 => $level->original,
                    31 => $level->two_player,
                    35 => $level->song,
                    36 => $level->extra_string,
                    37 => $level->coins,
                    38 => $level->rating->coin_verified ?? 0,
                    39 => $level->requested_stars,
                    42 => $level->rating->epic ?? 0,
                    43 => $level->rating->demon_difficulty ?? 0,
                    45 => $level->objects
                ], ':');
            })->join('|');

            $users = $query->pluck('user')
                ->map(function ($userID) {
                    $user = GameUser::whereId($userID)->first();
                    return $user->user_string ?? null;
                })->join('|');

            $songIds = $query->pluck('song');
            $customSongQuery = GameCustomSong::query()
                ->whereIn('song_id', $songIds)
                ->get();

            $songs = GameSong::query()
                ->whereIn('id', $songIds)
                ->get()
                ->union($customSongQuery)
                ->map(function ($song) {
                    /** @var GameSong|GameCustomSong $song */
                    return $song->toSongString();
                })->join('~:~');

            return "$result#$users#$songs#{$helper->generatePageHash($count, $page)}#{$hash->generateLevelListHash($query->get())}";
        } catch (ValidationException $e) {
            return ResponseCode::REQUEST_CHECK_FAILED;
        }
    }

    /**
     * @param GameLevelDownloadRequest $request
     * @param HashesController $hash
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/downloadGJLevel22
     */
    public function download(GameLevelDownloadRequest $request, HashesController $hash)
    {
        try {
            $request->validateChk();
        } catch (ChkValidationException $e) {
            return ResponseCode::CHK_CHECK_FAILED;
        }

        $levelString = $this->storageManager->get(sha1($request->level->id) . '.dat');
        if (!$levelString) {
            return ResponseCode::DOWNLOAD_LEVEL_MISSING_LEVEL_STRING;
        }

        $levelInfo = [
            1 => $request->level->id,
            2 => $request->level->name,
            3 => $request->level->desc,
            4 => $levelString,
            5 => $request->level->version,
            6 => $request->level->user,
            8 => ($request->level->rating->difficulty ?? 0) > 0 ? 10 : 0,
            9 => $request->level->rating->difficulty ?? 0,
            10 => $request->level->downloads,
            12 => $request->level->audio_track,
            13 => $request->level->game_version,
            14 => $request->level->likes,
            15 => $request->level->length,
            17 => $request->level->rating->demon ?? 0,
            18 => $request->level->rating->stars ?? 0,
            19 => $request->level->rating->featured_score ?? 0,
            25 => $request->level->rating->auto ?? 0,
            27 => $hash->encodeChk($request->level->password, $hash->keys['level_password']),
            28 => $request->level->created_at->diffForHumans(null, true),
            29 => $request->level->updated_at->diffForHumans(null, true),
            30 => $request->level->original,
            31 => $request->level->two_player,
            35 => $request->level->song,
            36 => $request->level->extra_string,
            37 => $request->level->coins,
            38 => $request->level->rating->coin_verified ?? 0,
            39 => $request->level->requested_stars,
            40 => $request->level->ldm,
            42 => $request->level->rating->epic ?? 0,
            43 => $request->level->rating->demon_difficulty ?? 0,
            45 => $request->level->objects
        ];

        $moreHash = 'GDCN';
        if (!empty($request->feaID)) {
            $levelInfo[41] = $request->feaID ?? 0;
            $moreHash = $request->level->creator->user_string;
        }

        $levelResult = GDObject::merge($levelInfo, ':');
        $levelStringHash = $hash->generateLevelStringHash($levelString);
        $levelHash = $hash->generateLevelHash($levelInfo[6], $levelInfo[18], $levelInfo[17], $levelInfo[1], $levelInfo[38], $levelInfo[19], $request->level->password, $levelInfo[41] ?? 0);

        return "{$levelResult}#{$levelStringHash}#{$levelHash}#{$moreHash}";
    }

    /**
     * @param GameLevelDeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJLevelUser20
     */
    public function delete(GameLevelDeleteRequest $request): int
    {
        try {
            if ($request->level->delete()) {
                $this->storageManager->delete(sha1($request->level->id) . '.dat');
                return ResponseCode::OK;
            }

            return ResponseCode::DELETE_FAILED;
        } catch (Exception $e) {
            return ResponseCode::DELETE_FAILED;
        }
    }

    /**
     * @param GameDailyLevelGetRequest $request
     * @return string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJDailyLevel
     */
    public function getDaily(GameDailyLevelGetRequest $request): string
    {
        $data = $request->validated();

        if ($data['weekly']) {
            $fea = GameWeeklyLevel::query()
                ->latest()
                ->first();

            $feaID = !empty($fea) ? $fea->id + config('game.weeklyIdOffset', 100000) : 0;
            $seconds = Carbon::rawParse('next monday')->diffInSeconds();
        } else {
            $fea = GameDailyLevel::query()
                ->latest()
                ->first();

            $feaID = $fea->id ?? 0;
            $seconds = Carbon::rawParse('tomorrow')->diffInSeconds();
        }

        return "{$feaID}|{$seconds}";
    }

    /**
     * @param GameLevelReportRequest $request
     * @param GameLog $log
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/reportGJLevel
     */
    public function report(GameLevelReportRequest $request, GameLog $log): int
    {
        $data = $request->validated();
        if ($log->existsOrNew(LogType::fromValue(LogType::REPORT_LEVEL), $data['levelID'], null, true)) {
            $report = GameLevelReport::query()
                ->firstOrCreate([
                    'level' => $data['levelID']
                ]);

            $report->increment('times');
            return ResponseCode::OK;
        }

        return ResponseCode::FAILED;
    }

    /**
     * @param GameLevelUpdateDescRequest $request
     * @param Helpers $helper
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/updateGJDesc20
     */
    public function updateDesc(GameLevelUpdateDescRequest $request, Helpers $helper): int
    {
        $data = $request->validated();

        if (!$request->user->can('update', $request->level)) {
            return ResponseCode::FAILED;
        }

        $request->level->desc = $data['levelDesc'];
        return $helper->bool2result($request->level->save());
    }
}
