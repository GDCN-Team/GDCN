<?php

namespace Database\Factories\Game;

use App\Models\Game\User;
use App\Models\Game\UserScore;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserScore::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user' => User::factory(),
            'game_version' => 21,
            'binary_version' => 35
        ];
    }
}
