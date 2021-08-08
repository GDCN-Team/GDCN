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
        if ($this->level->rated) {
            if (Arr::hasAnyValue($this->options, ['d', 'delete'])) {
                $this->level->rating->delete();
                return 'Rating deleted successfully!';
            }

            if (Arr::hasAnyValue($this->options, ['u', 'update'])) {
                $featured = Arr::hasAnyValue($this->options, ['f', 'featured']);
                $epic = Arr::hasAnyValue($this->options, ['e', 'epic']);
                $coin_verified = Arr::hasAnyValue($this->options, ['cv', 'coin_verified']);
                $demon_difficulty = Arr::getAny($this->arguments, ['dd', 'demon_difficulty']);

                $featured_score = Arr::getAny($this->arguments, ['fs', 'featured_score']);
                if (empty($featured_score) && $featured) {
                    $featured_score = 1;
                }

                $rating = $this->level->rating;
                $rating->epic = $epic;
                $rating->featured_score = $featured_score;
                $rating->coin_verified = $coin_verified;
                $rating->demon_difficulty = $helper->guessDemonDifficultyFromRating($demon_difficulty);
                $rating->save();

                return 'Rating updated successfully!';
            }
        }

        $stars = Arr::getAny($this->arguments, [0, 's', 'star', 'stars']);
        if (!empty($stars)) {
            $service->rate($this->level, $stars);
            return "Level rated successfully!";
        }

        return 'Argument "stars" is required';
    }
}
