<?php

namespace Database\Factories\Game;

use App\Models\Game\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Song::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'author_name' => $this->faker->name,
            'author_id' => mt_rand(),
            'size' => mt_rand() . '.00',
            'download_url' => $this->faker->url,
            'disabled' => false
        ];
    }
}
