<?php

namespace Database\Factories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Friend;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Friend::class;

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
