<?php

namespace Database\Factories\Game\Level;

use App\Models\Game\Level;
use App\Models\Game\Level\Weekly;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Weekly::class;

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
