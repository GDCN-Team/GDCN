<?php

namespace Database\Factories\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Score;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Score::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => Account::factory(),
            'level' => Level::factory(),
            'percent' => 0,
            'attempts' => 0,
            'coins' => 0
        ];
    }
}
