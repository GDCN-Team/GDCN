<?php

namespace Database\Factories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
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
            'account' => Account::factory(),
            'content' => Base64Url::encode($this->faker->word, true)
        ];
    }
}
