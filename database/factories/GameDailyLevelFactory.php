<?php

namespace Database\Factories;

use App\Models\GameDailyLevel;
use App\Models\GameLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameDailyLevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameDailyLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'level' => GameLevel::factory()
        ];
    }
}
