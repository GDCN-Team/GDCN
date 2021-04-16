<?php

namespace Database\Factories;

use App\Models\GameLevel;
use App\Models\GameWeeklyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameWeeklyLevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameWeeklyLevel::class;

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
