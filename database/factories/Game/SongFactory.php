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
            'song_id' => mt_rand(),
            'name' => $this->faker->name,
            'artist_name' => $this->faker->name,
            'artist_id' => mt_rand(),
            'size' => mt_rand() . '.00',
            'download_link' => $this->faker->url,
            'disabled' => false
        ];
    }
}
