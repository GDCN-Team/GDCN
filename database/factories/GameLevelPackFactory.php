<?php

namespace Database\Factories;

use App\Models\GameLevel;
use App\Models\GameLevelPack;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameLevelPackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameLevelPack::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        try {
            return [
                'name' => $this->faker->firstName,
                'levels' => GameLevel::factory(),
                'stars' => random_int(1, 10),
                'coins' => random_int(1, 2),
                'difficulty' => random_int(10, 60),
                'text_color' => '255,255,255',
                'bar_color' => '255,255,255'
            ];
        } catch (Exception $e) {
            return $this->definition();
        }
    }
}
