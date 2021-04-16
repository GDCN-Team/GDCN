<?php

namespace Database\Factories;

use App\Models\GameAccount;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameLevelCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameLevelComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'level' => GameLevel::factory(),
            'account' => GameAccount::factory(),
            'content' => Base64Url::encode($this->faker->word, true),
            'percent' => 0
        ];
    }
}
