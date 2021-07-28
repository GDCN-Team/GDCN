<?php

namespace Database\Factories\Game;

use App\Models\Game\Level;
use App\Models\Game\User;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Level::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user' => User::factory(),
            'game_version' => 21,
            'name' => $this->faker->firstName,
            'desc' => Base64Url::encode($this->faker->lastName, true),
            'extra_string' => Str::random(),
            'level_info' => Str::random()
        ];
    }
}
