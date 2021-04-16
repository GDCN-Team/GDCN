<?php

namespace Database\Factories;

use App\Models\GameAccount;
use App\Models\GameLevel;
use App\Models\GameLevelScore;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameLevelScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameLevelScore::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => GameAccount::factory(),
            'level' => GameLevel::factory(),
            'percent' => 0,
            'attempts' => 0,
            'coins' => 0
        ];
    }
}
