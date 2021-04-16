<?php

namespace Database\Factories;

use App\Models\GameAccountPermissionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameAccountPermissionGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccountPermissionGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word
        ];
    }
}
