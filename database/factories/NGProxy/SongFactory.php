<?php

namespace Database\Factories\NGProxy;

use App\Models\NGProxy\Song;
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
            'song_id' => mt_rand(),
            'artist_name' => $this->faker->name,
            'download_link' => $this->faker->url,
            'artist_id' => 7,
            'size' => '0.01',
            'disabled' => false
        ];
    }
}
