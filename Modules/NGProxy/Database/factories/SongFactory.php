<?php
namespace Modules\NGProxy\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\NGProxy\Entities\Song::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'author_id' => mt_rand(),
            'author_name' => $this->faker->name,
            'size' => $this->faker->randomFloat(2, 0, 100),
            'download_link' => $this->faker->url
        ];
    }
}

