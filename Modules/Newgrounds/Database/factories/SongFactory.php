<?php

namespace Modules\Newgrounds\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newgrounds\Entities\Song;

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
    public function definition()
    {
        return [
            //
        ];
    }
}

