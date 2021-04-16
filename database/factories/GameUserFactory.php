<?php

namespace Database\Factories;

use App\Models\GameUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameUser::class;

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
