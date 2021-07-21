<?php

namespace Database\Factories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Block;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Block::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => Account::factory(),
            'target_account' => Account::factory()
        ];
    }
}
