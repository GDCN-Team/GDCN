<?php

namespace Modules\NGProxy\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\NGProxy\Entities\CustomSong;

class CustomSongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomSong::class;

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

