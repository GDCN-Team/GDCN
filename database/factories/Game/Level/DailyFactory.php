<?php

namespace Database\Factories\Game\Level;

use App\Models\Game\Level;
use App\Models\Game\Level\Daily;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Daily::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'level' => Level::factory()
        ];
    }
}
