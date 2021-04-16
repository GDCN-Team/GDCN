<?php

namespace Database\Factories;

use App\Models\GameUser;
use App\Models\GameUserScore;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameUserScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameUserScore::class;

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
            'binary_version' => 35
        ];
    }
}
