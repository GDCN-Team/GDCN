<?php

namespace Database\Factories\Game;

use App\Models\Game\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $udid = 'S' . mt_rand();
        return [
            'name' => $this->faker->firstName,
            'udid' => $udid,
            'uuid' => $udid
        ];
    }
}
