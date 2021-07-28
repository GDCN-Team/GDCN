<?php

namespace App\Services\Game;

use App\Enums\Game\Level\SearchType;
use App\Enums\Game\Log\Types;
use App\Exceptions\Game\InvalidArgumentException;
use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\CustomSong;
use App\Models\Game\Level;
use App\Models\Game\Level\Daily;
use App\Models\Game\Level\Weekly;
use App\Models\Game\Log;
use App\Models\Game\Song;
use App\Models\Game\User;
use GDCN\GDObject;
use GDCN\Hash\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

/**
 * Class LevelService
 * @package App\Services
 */
class LevelService
{
    /**
     * LevelService constructor.
     * @param Hasher $hash
     * @param HelperService $helper
     * @param StorageService $storage
     */
    public function __construct(
        public Hasher $hash,
        public HelperService $helper,
        public StorageService $storage
    )
    {
    }

    /**
     * @param int $levelID
     * @return string
     */
    public function generateFileName(int $levelID): string
    {
        return "gdcn/levels/$levelID.dat";
    }

    /**
     * @param $user
     * @param $level
     * @param int $gameVersion
     * @param string $levelName
     * @param string $levelDesc
     * @param int $levelVersion
     * @param int $levelLength
     * @param int $audioTrack
     * @param int $songID
     * @param bool $auto
     * @param int $password
     * @param int $original
     * @param bool $twoPlayer
     * @param int $objects
     * @param int $coins
     * @param int $requestedStars
     * @param bool $unlisted
     * @param bool $ldm
     * @param string $extraString
     * @param string $levelInfo
     * @param string $levelString
     * @return Model|bool|Builder|Level
     */
    public function upload(
        $user,
        $level,
        int $gameVersion,
        string $levelName,
        string $levelDesc,
        int $levelVersion,
        int $levelLength,
        int $audioTrack,
        int $songID,
        bool $auto,
        int $password,
        int $original,
        bool $twoPlayer,
        int $objects,
        int $coins,
        int $requestedStars,
        bool $unlisted,
        bool $ldm,
        string $extraString,
        string $levelInfo,
        string $levelString
    ): Model|bool|Builder|Level
    {
        $userID = $this->helper->getID($user);
        $levelID = $this->helper->getID($level);

        $level = Level::where([
            'id' => $levelID,
            'user' => $userID
        ]);

        if (!$level->exists()) {
            $level = new Level();
        }

        $level->user = $user->id;
        $level->game_version = $gameVersion;
        $level->name = $levelName;
        $level->desc = $levelDesc;
        $level->version = $levelVersion;
        $level->length = $levelLength;
        $level->audio_track = $audioTrack;
        $level->song = $songID;
        $level->auto = $auto;
        $level->password = $password;
        $level->original = $original;
        $level->two_player = $twoPlayer;
        $level->objects = $objects;
        $level->coins = $coins;
        $level->requested_stars = $requestedStars;
        $level->unlisted = $unlisted;
        $level->ldm = $ldm;
        $level->extra_string = $extraString;
        $level->level_info = $levelInfo;
        $level->save();

        $this->storage->put(
            $this->generateFileName($level->id),
            $levelString
        );

        return $level;
    }

    /**
     * @param SearchType $type
     * @param string $str
     * @param int $page
     * @param Account|int|null $account
     * @param string|null $followed
     * @param string|null $len
     * @param bool|null $star
     * @param string|null $diff
     * @param int|null $demonFilter
     * @param bool $unCompleted
     * @param bool $onlyCompleted
     * @param string|null $completedLevels
     * @param bool $featured
     * @param bool $original
     * @param bool $epic
     * @param int|null $song
     * @param bool $customSong
     * @param bool $noStar
     * @param bool $coins
     * @param bool $twoPlayer
     * @return string
     * @throws InvalidArgumentException
     * @throws NoItemException
     */
    public function search(
        SearchType $type,
        string $str,
        int $page,

        Account|int|null $account,
        ?string $followed,

        // Filters

        ?string $len,
        ?bool $star,
        ?string $diff,
        ?int $demonFilter,

        // Advanced Options

        ?bool $unCompleted,
        ?bool $onlyCompleted,
        ?string $completedLevels,
        ?bool $featured,
        ?bool $original,
        ?bool $epic,
        ?int $song,
        ?bool $customSong,
        ?bool $noStar,
        ?bool $coins,
        ?bool $twoPlayer
    ): string
    {
        $query = Level::query();

        switch ($type->value) {
            case SearchType::SEARCH:
                is_numeric($str) ? $query->find($str) : $query->where('name', 'LIKE', '%' . $str . '%');
                break;
            case SearchType::MOST_DOWNLOADED:
                $query->orderByDesc('downloads');
                break;
            case SearchType::MOST_LIKED:
                $query->orderByDesc('likes');
                break;
            case SearchType::TRENDING:
                $query->where('created_at', '>', Carbon::now()->subWeek());
                $query->orderByDesc('likes');
                break;
            case SearchType::RECENT:
                $query->orderByDesc('created_at');
                break;
            case SearchType::USER:
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
                $query->whereHas('creator', function (Builder $query) use ($followed) {
                    $query->whereIn('uuid', explode(',', $followed));
                });
                break;
            case SearchType::FRIENDS:
                $account = $this->helper->getModel($account, Account::class);
                $query->whereHas('creator', function (Builder $query) use ($account) {
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

        // Filters

        if ($len && $len !== '-') {
            $query->whereLength($len);
        }

        if ($star) {
            $query->whereHas('rating', function (Builder $query) use ($star) {
                $query->where('stars', $star);
            });
        }

        if ($diff && $diff !== '-') {
            switch ($diff) {
                case -1: // N/A
                    $query->whereHas('rating', function (Builder $query) {
                        $query->orWhere('stars', '<=', 0);
                    });
                    break;
                case -2: // Demon
                    $query->whereHas('rating', function (Builder $query) use ($demonFilter) {
                        $diff = $this->helper->guessDemonDifficultyFromRating($demonFilter);
                        $query->where('demon_difficulty', $diff);
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

        if ($unCompleted && $completedLevels) {
            $query->whereNotIn('id', explode(',', $completedLevels));
        }

        if ($onlyCompleted && $completedLevels) {
            $query->whereIn('id', explode(',', $completedLevels));
        }

        if ($featured) {
            $query->whereHas('rating', function (Builder $query) {
                $query->where('featured_score', '>', 0);
            });
        }

        if ($original) {
            $query->whereNotNull('original');
        }

        if ($epic) {
            $query->whereHas('rating', function (Builder $query) {
                $query->where('epic', true);
            });
        }

        if ($song) {
            if ($customSong) {
                $query->whereSong($song);
            } else {
                $query->whereAudioTrack($song);
            }
        }

        if ($noStar) {
            $query->whereHas('rating', function (Builder $query) {
                $query->orWhere('stars', '<=', 0);
            });
        }

        if ($coins) {
            $query->where('coins', '!=', 0);
        }

        if ($twoPlayer) {
            $query->where('two_player', true);
        }

        $count = $query->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        $hash = null;
        $levels = $query->forPage(++$page, $this->helper->perPage)->with('rating')->get();
        $result = $levels->map(function (Level $level) use (&$hash) {
            $hash .= implode(null, [substr($level->id, 0, 1), substr($level->id, -1), $level->rating->stars ?? 0, $level->rating->coin_verified ?? false]);

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

        $users = $levels->map(function (Level $level) {
            return $level->creator->user_string ?? null;
        })->join('|');

        $songIds = $levels->pluck('song');
        $songs = Song::query()
            ->whereIn('id', $songIds)
            ->get()
            ->union(CustomSong::query()
                ->whereIn('song_id', $songIds)
                ->get())
            ->map(function (Song|CustomSong $song) {
                return $song->toSongString();
            })->join('~:~');

        return $result . "#" . $users . "#" . $songs . "#" . $this->helper->generatePageHash($count, $page) . "#" . $this->hash->generateHashForSearchLevels($hash);
    }

    /**
     * @param int $levelID
     * @return string|null
     */
    public function getLevelString(int $levelID): ?string
    {
        return $this->storage->get(
            $this->generateFileName($levelID)
        );
    }

    /**
     * @param $level
     * @return string
     */
    public function download($level): string
    {
        $level = $this->helper->getModel($level, Level::class);
        $levelString = $this->getLevelString($level->id);

        $log = Log::firstOrNew([
            'type' => Types::DOWNLOADED_LEVEL,
            'value' => $level->id,
            'ip' => Request::ip()
        ]);

        if (!$log->exists()) {
            ++$level->likes;
            $level->save();
            $log->save();
        }

        $levelInfo = [
            1 => $level->id,
            2 => $level->name,
            3 => $level->desc,
            4 => $levelString,
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
            27 => $this->hash->encodeLevelPassword($level->password),
            28 => $level->created_at->diffForHumans(null, true),
            29 => $level->updated_at->diffForHumans(null, true),
            30 => $level->original,
            31 => $level->two_player,
            35 => $level->song,
            36 => $level->extra_string,
            37 => $level->coins,
            38 => $level->rating->coin_verified ?? 0,
            39 => $level->requested_stars,
            40 => $level->ldm,
            42 => $level->rating->epic ?? 0,
            43 => $level->rating->demon_difficulty ?? 0,
            45 => $level->objects
        ];

        $moreHash = 'GDCN';
        if (!empty($request->feaID)) {
            $levelInfo[41] = $request->feaID ?? 0;
            $moreHash = $level->creator->user_string;
        }

        $levelResult = GDObject::merge($levelInfo, ':');
        $levelStringHash = $this->hash->generateLevelStringHashForDownloadLevel($levelString);
        $levelHash = implode(',', [
            $levelInfo[6],
            $levelInfo[18],
            $levelInfo[17],
            $levelInfo[1],
            $levelInfo[38],
            $levelInfo[19],
            $level->password,
            $levelInfo[41] ?? 0
        ]);

        $levelHash = $this->hash->generateHashForDownloadLevel($levelHash);
        return $levelResult . '#' . $levelStringHash . '#' . $levelHash . '#' . $moreHash;
    }

    /**
     * @param $user
     * @param $level
     * @return bool
     */
    public function delete($user, $level): bool
    {
        $user = $this->helper->getModel($user, User::class);
        $level = $this->helper->getModel($level, Level::class);

        // Check trans in
        if (Str::startsWith($level->level_info, 'trans_in:')) {
            $logID = explode(':', $level->level_info)[1];
            $log = Log::find($logID);
            if ($log->exists()) {
                if (Str::contains($log->value, $level->original)) {
                    $log->value .= ':deleted';
                    $log->save();
                }
            }
        }

        if ($user->can('delete', $level)) {
            $this->storage->delete('gdcn/levels/' . $level->id . '.dat');
            return $level->delete();
        }

        return false;
    }

    /**
     * @return string
     */
    public function getDaily(): string
    {
        $fea = Daily::query()
            ->latest()
            ->first();

        $feaID = $fea->id ?? 0;
        $seconds = Carbon::rawParse('tomorrow')->diffInSeconds();
        return $feaID . '|' . $seconds;
    }

    /**
     * @return string
     */
    public function getWeekly(): string
    {
        $fea = Weekly::query()
            ->latest()
            ->first();

        $feaID = !empty($fea) ? $fea->id + config('game.weeklyIdOffset', 100000) : 0;
        $seconds = Carbon::rawParse('next monday')->diffInSeconds();
        return $feaID . '|' . $seconds;
    }

    /**
     * @param $level
     * @return bool
     */
    public function report($level): bool
    {
        $level = $this->helper->getModel($level, Level::class);

        $log = Log::firstOrNew([
            'type' => Types::REPORT_LEVEL,
            'value' => $level->id,
            'ip' => Request::ip()
        ]);

        if (!$log->exists()) {
            $report = new Level\Report();
            $report->level = $level->id;
            ++$report->times;
            $report->save();

            return $log->save();
        }

        return false;
    }

    /**
     * @param $user
     * @param $level
     * @param string $desc
     * @return bool
     */
    public function updateDesc($user, $level, string $desc): bool
    {
        $user = $this->helper->getModel($user, User::class);
        $level = $this->helper->getModel($level, Level::class);

        if ($user->can('update', $level)) {
            $level->desc = $desc;
            return $level->save();
        }

        return false;
    }
}
