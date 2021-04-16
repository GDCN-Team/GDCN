<?php

namespace Database\Factories;

use App\Models\GameLevel;
use App\Models\GameLevelGauntlet;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameLevelGauntletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameLevelGauntlet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'level1' => GameLevel::factory(),
            'level2' => GameLevel::factory(),
            'level3' => GameLevel::factory(),
            'level4' => GameLevel::factory(),
            'level5' => GameLevel::factory()
        ];
    }
}
