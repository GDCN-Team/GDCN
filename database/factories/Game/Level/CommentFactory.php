<?php

namespace Database\Factories\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'level' => Level::factory(),
            'account' => Account::factory(),
            'content' => Base64Url::encode($this->faker->word, true),
            'percent' => 0
        ];
    }
}
