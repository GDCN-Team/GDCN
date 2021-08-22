<?php

namespace App\Services\Game;

use App\Enums\Game\Level\SearchType;
use App\Enums\Game\LogType;
use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Daily;
use App\Models\Game\Level\Report;
use App\Models\Game\Level\Weekly;
use App\Models\Game\Log;
use App\Models\Game\User;
use App\Services\Game\Level\RatingService;
use GDCN\GDObject;
use GDCN\Hash\Components\LevelInfo as LevelInfoComponent;
use GDCN\Hash\Components\LevelPassword as LevelPasswordComponent;
use GDCN\Hash\Components\LevelString as LevelStringComponent;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Request;

class LevelService
{
    public function __construct(
        public HelperService  $helper,
        public StorageService $storage,
        public SongService    $songService
    )
    {
    }

    protected function generateObjectNameForOss(int $levelID): string
    {
        return "gdcn/levels/$levelID.dat";
    }

    public function upload(
        ?string $uuid,
        int     $levelID,
        int     $gameVersion,
        string  $levelName,
        ?string $levelDesc,
        int     $levelVersion,
        int     $levelLength,
        int     $audioTrack,
        int     $songID,
        bool    $auto,
        int     $password,
        int     $original,
        bool    $twoPlayer,
        int     $objects,
        int     $coins,
        int     $requestedStars,
        bool    $unlisted,
        bool    $ldm,
        string  $extraString,
        ?string $levelInfo,
        string  $levelString
    ): ?Level
    {
        $user = $this->helper->resolveUser($uuid);

        if (!$levelInfo) {
            LogFacade::channel('gdcn')
                ->notice('[Level System] Action: Upload Level Failed', [
                    'userID' => $user->id,
                    'levelID' => $levelID,
                    'gameVersion' => $gameVersion,
                    'name' => $levelName,
                    'desc' => $levelDesc,
                    'version' => $levelVersion,
                    'length' => $levelLength,
                    'audioTrack' => $audioTrack,
                    'song' => $songID,
                    'auto' => $auto,
                    'password' => $password,
                    'original' => $original,
                    'twoPlayer' => $twoPlayer,
                    'objects' => $objects,
                    'coins' => $coins,
                    'requestedStars' => $requestedStars,
                    'unlisted' => $unlisted,
                    'ldm' => $ldm,
                    'extraString' => $extraString,
                    'levelInfo' => $levelInfo,
                    'levelString' => $levelString,
                    'reason' => 'Detected Verify Hack'
                ]);

            return null;
        }

        $attributes = [
            'name' => $levelName,
            'user' => $user->id
        ];

        if (!empty($levelID)) {
            $attributes['id'] = $levelID;
        }

        $level = Level::updateOrCreate($attributes, [
            'game_version' => $gameVersion,
            'desc' => $levelDesc,
            'version' => $levelVersion,
            'length' => $levelLength,
            'audio_track' => $audioTrack,
            'song' => $songID,
            'auto' => $auto,
            'password' => $password,
            'original' => $original,
            'two_player' => $twoPlayer,
            'objects' => $objects,
            'coins' => $coins,
            'requested_stars' => $requestedStars,
            'unlisted' => $unlisted,
            'ldm' => $ldm,
            'extra_string' => $extraString,
            'level_info' => $levelInfo
        ]);

        $this->storage->put(
            $this->generateObjectNameForOss($level->id),
            $levelString
        );

        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Upload Level', [
                'userID' => $user->id,
                'levelID' => $levelID,
                'gameVersion' => $gameVersion,
                'name' => $levelName,
                'desc' => $levelDesc,
                'version' => $levelVersion,
                'length' => $levelLength,
                'audioTrack' => $audioTrack,
                'song' => $songID,
                'auto' => $auto,
                'password' => $password,
                'original' => $original,
                'twoPlayer' => $twoPlayer,
                'objects' => $objects,
                'coins' => $coins,
                'requestedStars' => $requestedStars,
                'unlisted' => $unlisted,
                'ldm' => $ldm,
                'extraString' => $extraString,
                'levelInfo' => $levelInfo,
                'levelString' => $levelString
            ]);

        return $level;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function search(
        SearchType $type,
        ?string    $str,
        int        $page,
        int        $accountID,
        ?string    $followed,
        ?string    $len,
        ?bool      $star,
        ?string    $diff,
        ?int       $demonFilter,
        ?bool      $unCompleted,
        ?bool      $onlyCompleted,
        ?string    $completedLevels,
        ?bool      $featured,
        ?bool      $original,
        ?bool      $epic,
        ?int       $song,
        ?bool      $customSong,
        ?bool      $noStar,
        ?bool      $coins,
        ?bool      $twoPlayer
    ): string
    {
        $query = Level::query();
        $showUnlisted = false;

        switch ($type->value) {
            case SearchType::SEARCH:
                $query->whereKey($str);
                $query->where('name', 'LIKE', $str . '%');
                break;
            case SearchType::MOST_DOWNLOADED:
                $query->orderByDesc('downloads');
                break;
            case SearchType::MOST_LIKED:
                $query->orderByDesc('likes');
                break;
            case SearchType::TRENDING:
                $query->where('created_at', '>', app(Carbon::class)->subWeek());
                $query->orderByDesc('likes');
                break;
            case SearchType::RECENT:
                $query->orderByDesc('created_at');
                break;
            case SearchType::USER:
                $account = Account::findOrFail($accountID);
                if ($account->user->id === $str) {
                    $showUnlisted = true;
                }

                $query->where('user', $str);
                break;
            case SearchType::FEATURED:
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('featured_score', '>', 0);
                });
                break;
            case SearchType::MAGIC:
                $query->where('objects', '>', 9999);
                break;
            case SearchType::PACK:
                $query->whereIn('id', explode(',', $str));
                break;
            case SearchType::AWARDED:
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('stars', '>', 0);
                });
                break;
            case SearchType::FOLLOWED:
                $query->whereHas('user', function (Builder $query) use ($followed) {
                    $query->whereIn('uuid', explode(',', $followed));
                });
                break;
            case SearchType::FRIENDS:
                $account = Account::findOrFail($accountID);
                $query->whereHas('user', function (Builder $query) use ($account) {
                    $query->whereIn('uuid', $account->friends->pluck('id'));
                });
                break;
            case SearchType::HALL_OF_FAME:
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('epic', true);
                });
                break;
            default:
                throw new InvalidArgumentException();
        }

        if (!$showUnlisted) {
            $query->where('unlisted', '!=', true);
        }

        // Filters

        if (!empty($len) && $len !== '-') {
            $query->whereLength($len);
        }

        if ($star === true) {
            $query->whereHas('rating', function (Builder $query) use ($star) {
                $query->where('stars', $star);
            });
        }

        if (!empty($diff) && $diff !== '-') {
            switch ($diff) {
                case -1: // N/A
                    $query->whereDoesntHave('rating');
                    break;
                case -2: // Demon
                    $query->whereHas('rating', function (Builder $query) use ($demonFilter) {
                        $diff = app(RatingService::class)->guessDemonDifficultyFromRating($demonFilter);
                        $query->where('demon_difficulty', $diff);
                    });
                    break;
                case -3: // Auto
                    $query->whereHas('rating', function (Builder $query) use ($demonFilter) {
                        $query->where('stars', 1);
                        $query->where('auto', true);
                    });
                    break;
                default:
                    $query->whereHas('rating', function (Builder $query) use ($diff) {
                        $difficulties = explode(',', strtr($diff, [',' => '0,']) . '0');
                        $query->where('difficulty', $difficulties);
                        $query->where('auto', false);
                        $query->where('demon', false);
                    });
            }
        }

        // Advanced Options

        if ($unCompleted === true && !empty($completedLevels)) {
            $query->whereNotIn('id', explode(',', $completedLevels));
        }

        if ($onlyCompleted === true && !empty($completedLevels)) {
            $query->whereIn('id', explode(',', $completedLevels));
        }

        if ($featured === true) {
            $query->whereHas('rating', function (Builder $query) {
                $query->where('featured_score', '>', 0);
            });
        }

        if ($original === true) {
            $query->whereNotNull('original');
        }

        if ($epic === true) {
            $query->whereHas('rating', function (Builder $query) {
                $query->where('epic', true);
            });
        }

        if (!empty($song)) {
            if ($customSong === true) {
                $query->whereSong($song);
            } else {
                $query->whereAudioTrack($song);
            }
        }

        if ($noStar === true) {
            $query->whereHas('rating', function (Builder $query) {
                $query->orWhere('stars', '<=', 0);
            });
        }

        if ($coins === true) {
            $query->where('coins', '!=', 0);
        }

        if ($twoPlayer === true) {
            $query->where('two_player', true);
        }

        $hash = '';
        $users = [];
        $songs = [];

        $result = $query->forPage(++$page, PageInfoComponent::$per_page)
            ->with('rating')
            ->get()
            ->map(function (Level $level) use (&$hash, &$users, &$songs) {
                $hash .= implode(null, [
                    substr($level->id, 0, 1),
                    substr($level->id, -1),
                    $level->rating->stars ?? 0,
                    $level->rating->coin_verified ?? 0
                ]);

                /** @var User $user */
                $user = $level->getRelationValue('user');
                $users[] = implode(':', [
                    $user->id,
                    $user->name,
                    is_numeric($user->uuid) ? $user->uuid : 0
                ]);

                if ($level->song > 0) {
                    $songs[] = $this->songService->get($level->song);
                }

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

        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Search Level', [
                'query' => $query->toSql()
            ]);

        return implode('#', [
            $result,
            implode('|', $users),
            implode('~:~', $songs),
            app(PageInfoComponent::class)->generate(Level::count(), $page),
            app(LevelInfoComponent::class)->generateHash($hash)
        ]);
    }

    public function getLevelString(int $levelID): ?string
    {
        return $this->storage->get(
            $this->generateObjectNameForOss($levelID)
        );
    }

    public function download(int $levelID): string
    {
        /** @var Daily|Weekly $feature */
        $feature = null;
        switch ($levelID) {
            case -1: # Daily
                $today = Carbon::today();
                $feature = Daily::whereDate('time', $today)
                    ->first();
                break;
            case -2: # Weekly
                $monday = app(Carbon::class)->startOfWeek();
                $feature = Weekly::whereDate('time', $monday)
                    ->first();
                break;
        }

        $level = Level::findOrFail($levelID);
        $levelString = $this->getLevelString($level->id);

        Log::where([
            'type' => LogType::DOWNLOADED_LEVEL,
            'value' => $level->id,
            'ip' => Request::ip()
        ])->existsOr(function () use (&$level) {
            ++$level->downloads;
            $level->save();

            Log::create([
                'type' => LogType::DOWNLOADED_LEVEL,
                'value' => $level->id,
                'ip' => Request::ip()
            ]);
        });

        $result = GDObject::merge([
            1 => $level->id,
            2 => $level->name,
            3 => $level->desc,
            4 => $levelString,
            5 => $level->version,
            6 => $level->user,
            8 => $level->rating?->difficulty > 0 ? 10 : 0,
            9 => $level->rating?->difficulty,
            10 => $level->downloads,
            12 => $level->audio_track,
            13 => $level->game_version,
            14 => $level->likes,
            15 => $level->length,
            17 => $level->rating?->demon,
            18 => $level->rating?->stars,
            19 => $level->rating?->featured_score,
            25 => $level->rating?->auto,
            27 => app(LevelPasswordComponent::class)->encode($level->password),
            28 => $level->created_at->locale('en')->diffForHumans(syntax: true),
            29 => $level->updated_at->locale('en')->diffForHumans(syntax: true),
            30 => $level->original,
            31 => $level->two_player,
            35 => $level->song,
            36 => $level->extra_string,
            37 => $level->coins,
            38 => $level->rating?->coin_verified,
            39 => $level->requested_stars,
            40 => $level->ldm,
            41 => $feature?->id,
            42 => $level->rating?->epic,
            43 => $level->rating?->demon_difficulty,
            45 => $level->objects
        ], ':');

        $levelInfo = implode(',', [
            $level->user,
            $level->rating?->stars ?? 0,
            $level->rating?->demon ?? 0,
            $level->id,
            $level->rating?->coin_verified ?? 0,
            $level->rating?->featured_score ?? 0,
            $level->password,
            $feature?->id ?? 0
        ]);

        $moreHash = config('app.name');
        if (!empty($feature?->id)) {
            /** @var User $user */
            $user = $level->getRelationValue('user');

            $moreHash = implode(':', [
                $user->id,
                $user->name,
                is_numeric($user->uuid) ? $user->uuid : 0
            ]);
        }

        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Download Level', [
                'ip' => Request::ip(),
                'levelID' => $level->id
            ]);

        return implode('#', [
            $result,
            app(LevelStringComponent::class)->generateHash($levelString),
            app(LevelInfoComponent::class)->generateHash($levelInfo),
            $moreHash
        ]);
    }

    public function delete(?string $uuid, int $levelID): bool
    {
        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Delete Level', [
                'levelID' => $levelID
            ]);

        return $this->helper->resolveUser($uuid)
            ->levels()
            ->whereKey($levelID)
            ->delete();
    }

    public function getDaily(): string
    {
        $feature = Daily::query()
            ->latest()
            ->first();

        $featureID = 0;
        if (!empty($feature)) {
            $featureID = $feature->id;
        }

        $seconds = app(Carbon::class)
            ->secondsUntilEndOfDay();

        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Get Daily Level', [
                'dailyID' => $featureID,
                'seconds' => $seconds
            ]);

        return $featureID . '|' . $seconds;
    }

    public function getWeekly(): string
    {
        $feature = Weekly::query()
            ->latest()
            ->first();

        $featureID = 0;
        if (!empty($feature)) {
            $featureID = config('game.weeklyIdOffset', 100000) + $feature->id;
        }

        $seconds = app(Carbon::class)
            ->addWeek()
            ->startOfWeek()
            ->diffInSeconds();

        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Get Weekly Level', [
                'weeklyID' => $featureID,
                'seconds' => $seconds
            ]);

        return $featureID . '|' . $seconds;
    }

    public function report(int $levelID): bool
    {
        /** @var Report|Builder $report */
        $report = Level::findOrFail($levelID)
            ->report()
            ->firstOrCreate();

        Log::where([
            'type' => LogType::REPORT_LEVEL,
            'value' => $levelID,
            'ip' => Request::ip()
        ])->existsOr(function () use (&$report, $levelID) {
            ++$report->times;

            Log::create([
                'type' => LogType::REPORT_LEVEL,
                'value' => $levelID,
                'ip' => Request::ip()
            ]);
        });

        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Report Level', [
                'ip' => Request::ip(),
                'levelID' => $levelID
            ]);

        return $report->save();
    }

    public function updateDesc(?string $uuid, int $levelID, ?string $desc): bool
    {
        LogFacade::channel('gdcn')
            ->info('[Level System] Action: Upload Desc', [
                'levelID' => $levelID,
                'desc' => $desc
            ]);

        return $this->helper->resolveUser($uuid)
            ->levels()
            ->whereKey($levelID)
            ->update([
                'desc' => $desc
            ]);
    }
}
