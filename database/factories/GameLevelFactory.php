<?php

namespace Database\Factories;

use App\Models\GameLevel;
use App\Models\GameUser;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GameLevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user' => GameUser::factory(),
            'game_version' => 21,
            'name' => $this->faker->firstName,
            'desc' => Base64Url::encode($this->faker->lastName, true),
            'extra_string' => Str::random(),
            'level_info' => Str::random()
        ];
    }
}
