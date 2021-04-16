<?php

namespace Database\Factories;

use App\Models\GameAccount;
use App\Models\GameAccountBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameAccountBlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccountBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => GameAccount::factory(),
            'target_account' => GameAccount::factory()
        ];
    }
}
