<?php

namespace App\Services\Game\Level;

use App\Exceptions\Game\CommandNotFoundException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\Level\Daily;
use App\Models\Game\Level\Weekly;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class CommentCommandService
{
    public function __construct(
        protected Account      $operator,
        protected LevelComment $comment,
        protected Level        $level,
        protected string       $command,
        protected array        $arguments,
        protected array        $options
    )
    {
    }

    /**
     * @throws CommandNotFoundException
     */
    public function execute(): mixed
    {
        if (!in_array($this->command, ['__construct', 'execute']) && method_exists($this, $this->command)) {
            $this->comment->delete();
            return App::call([$this, $this->command]);
        }

        throw new CommandNotFoundException();
    }

    public function test(): string
    {
        return 'worked!';
    }

    public function rate(): string
    {
        if (!$this->operator->permission_group?->can('COMMAND_RATE_LEVEL')) {
            return 'Permission denied!';
        }

        $isUpdate = Arr::hasAnyValue($this->options, ['u', 'update']);
        $isDelete = Arr::hasAnyValue($this->options, ['d', 'delete']);
        $isInverse = Arr::hasAnyValue($this->options, ['i', 'inverse']);

        $stars = Arr::getAny($this->arguments, ['s', 'star', 'stars']);
        $featured = Arr::hasAnyValue($this->options, ['f', 'featured']);
        $featured_score = Arr::getAny($this->arguments, ['fs', 'featured_score']);
        $epic = Arr::hasAnyValue($this->options, ['e', 'epic']);
        $coin_verified = Arr::hasAnyValue($this->options, ['cv', 'coin_verified']);
        $demon_difficulty = Arr::getAny($this->arguments, ['dd', 'demon_difficulty']);

        if (!$this->level->rating) {
            $stars = intval($stars);
            if (empty($stars) || !is_int($stars) || $stars < 0 || $stars > 10) {
                return 'Invalid stars count.';
            }

            $ratingService = app(RatingService::class);
            $rating = $this->level
                ->rating()
                ->create([
                    'stars' => $stars,
                    'difficulty' => $ratingService->guessDifficultyFromStars($stars),
                    'featured_score' => $featured_score ?? ($featured ? 1 : 0),
                    'epic' => $epic,
                    'coin_verified' => $coin_verified,
                    'auto' => $stars === 1,
                    'demon' => $stars === 10,
                    'demon_difficulty' => $ratingService->guessDemonDifficultyFromRating($demon_difficulty)
                ]);

            if (!$rating->save()) {
                return 'Rate failed, unknown error.';
            }

            return 'Rate successful!';
        } elseif ($isUpdate) {
            $data = [];
            $ratingService = app(RatingService::class);
            if (!empty($stars) && is_int($stars) && $stars > 0 && $stars <= 10) {
                $data['stars'] = $stars;
                $data['difficulty'] = $ratingService->guessDifficultyFromStars($stars);
                $data['auto'] = $stars === 1;
                $data['demon'] = $stars === 10;
            }

            if (!empty($featured) && is_bool($featured) || !empty($featured_score) && is_int($featured_score)) {
                if (!empty($featured_score)) {
                    $data['featured_score'] = $featured_score;
                } else {
                    $data['featured_score'] = $featured === !$isInverse;
                }
            }

            if (!empty($epic) && is_bool($epic)) {
                $data['epic'] = $epic === !$isInverse;
            }

            if (!empty($coin_verified) && is_bool($coin_verified)) {
                $data['coin_verified'] = $coin_verified === !$isInverse;
            }

            if (!empty($demon_difficulty) && is_int($demon_difficulty) && $demon_difficulty > 0 && $demon_difficulty <= 5) {
                $data['demon_difficulty'] = $ratingService->guessDemonDifficultyFromRating($demon_difficulty);
            }

            $updated = $this->level
                ->rating()
                ->update($data);

            Log::channel('gdcn')
                ->notice('[Level Comment Command System] Action: Rating Updated', [
                    'operatorAccountID' => $this->operator->id,
                    'levelID' => $this->level->id,
                    'data' => $data
                ]);

            if (!$updated) {
                return 'Rate update failed, unknown error.';
            }

            return 'Rate update successfully!';
        } elseif ($isDelete) {
            if (!$this->level->rating->delete()) {
                return 'UnRate failed, unknown error.';
            }

            return 'UnRate successful!';
        }

        return 'Unknown error.';
    }

    public function set(): string
    {
        switch ($this->arguments[1]) {
            case 'as':
                switch ($this->arguments[2]) {
                    case 'daily':
                        if (!$this->operator->permission_group?->can('COMMAND_SET_AS_DAILY_LEVEL')) {
                            return 'Permission denied.';
                        }

                        $time = app(Carbon::class)->addDay();
                        if ($feature = Level\Daily::latest()) {
                            $time = $feature->time?->addDay() ?? $time;
                        }

                        /** @var Daily $daily */
                        $daily = $this->level
                            ->daily()
                            ->create([
                                'time' => $time
                            ]);

                        return "Set to daily! ID: $daily->id, time: $daily->time";
                    case 'weekly':
                        if (!$this->operator->permission_group?->can('COMMAND_SET_AS_WEEKLY_LEVEL')) {
                            return 'Permission denied.';
                        }

                        $time = app(Carbon::class)
                            ->addWeek()
                            ->startOfWeek();

                        if ($feature = Weekly::latest()) {
                            $time = $feature->time?->addWeek()->startOfWeek() ?? $time;
                        }

                        /** @var Weekly $weekly */
                        $weekly = $this->level
                            ->weekly()
                            ->create([
                                'time' => $time
                            ]);

                        return "Set to weekly! ID: $weekly->id, time: $weekly->time";
                }
        }

        return 'Error';
    }
}
