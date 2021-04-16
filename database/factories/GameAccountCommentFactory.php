<?php

namespace Database\Factories;

use App\Models\GameAccount;
use App\Models\GameAccountComment;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameAccountCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccountComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => GameAccount::factory(),
            'content' => Base64Url::encode($this->faker->word, true)
        ];
    }
}
