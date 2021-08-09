<?php

namespace App\Services\Game\Level;

use App\Exceptions\Game\InvalidCommandException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Services\Game\HelperService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class CommentCommandService
{
    protected array $executable = [
        'test',
        'rate'
    ];

    public function __construct(
        protected Account      $operator,
        protected Level        $level,
        protected LevelComment $comment,
        protected string       $command,
        protected array        $arguments,
        protected array        $options
    )
    {
    }

    /**
     * @return mixed
     * @throws InvalidCommandException
     */
    public function execute(): mixed
    {
        if (in_array($this->command, $this->executable) && method_exists($this, $this->command)) {
            $this->comment->delete();
            return App::call([$this, $this->command]);
        }

        throw new InvalidCommandException();
    }

    /**
     * @return string
     */
    public function test(): string
    {
        return 'worked!';
    }

    /**
     * @param HelperService $helper
     * @param RatingService $service
     * @return string
     */
    public function rate(
        HelperService $helper,
        RatingService $service
    ): string
    {
        if (!optional($this->operator->permission)->can('COMMAND_RATE_LEVEL')) {
            return 'Permission denied!';
        }

        if ($this->level->rated) {
            if (Arr::hasAnyValue($this->options, ['d', 'delete'])) {
                $this->level->rating->delete();
                return 'Rating deleted successfully!';
            }

            if (Arr::hasAnyValue($this->options, ['u', 'update'])) {
                $stars = Arr::getAny($this->arguments, ['s', 'star', 'stars']);
                $featured = Arr::hasAnyValue($this->options, ['f', 'featured']);
                $epic = Arr::hasAnyValue($this->options, ['e', 'epic']);
                $coin_verified = Arr::hasAnyValue($this->options, ['cv', 'coin_verified']);
                $demon_difficulty = Arr::getAny($this->arguments, ['dd', 'demon_difficulty']);

                if (Arr::hasAnyValue($this->options, ['i', 'inverse'])) {
                    $featured = !$featured;
                    $epic = !$epic;
                    $coin_verified = !$coin_verified;
                }

                $featured_score = Arr::getAny($this->arguments, ['fs', 'featured_score']);
                if (empty($featured_score) && $featured) {
                    $featured_score = 1;
                }

                $rating = $this->level->rating;
                if (!empty($stars)) {
                    $rating->stars = $stars;
                    [$name, $diff, $auto, $demon] = $helper->guessDiffFromStars($stars);

                    $rating->difficulty = $diff;
                    $rating->auto = $auto;
                    $rating->demon = $demon;
                }

                if (!empty($featured_score)) {
                    $rating->featured_score = $featured_score;
                }

                if (!empty($epic)) {
                    $rating->featured_score = $featured_score;
                }

                if (!empty($coin_verified)) {
                    $rating->coin_verified = $coin_verified;
                }

                if (!empty($demon_difficulty)) {
                    $rating->demon_difficulty = $helper->guessDemonDifficultyFromRating($demon_difficulty);
                }

                $rating->save();
                return 'Rating updated successfully!';
            }
        }

        $stars = Arr::getAny($this->arguments, [1, 's', 'star', 'stars']);
        if (!empty($stars)) {
            $rating = $service->rate($this->level, (int)$stars);
            if (!empty($rating)) {
                return "Level rated successfully!";
            }
        }

        return 'Argument "stars" is required';
    }
}
