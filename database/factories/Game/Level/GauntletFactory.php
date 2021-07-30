<?php

namespace Database\Factories\Game\Level;

use App\Models\Game\Level;
use App\Models\Game\Level\Gauntlet;
use Illuminate\Database\Eloquent\Factories\Factory;

class GauntletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gauntlet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'gauntlet_id' => mt_rand(),
            'level1' => Level::factory(),
            'level2' => Level::factory(),
            'level3' => Level::factory(),
            'level4' => Level::factory(),
            'level5' => Level::factory()
        ];
    }
}
