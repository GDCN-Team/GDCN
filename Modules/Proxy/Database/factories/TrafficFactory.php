<?php

namespace Modules\Proxy\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Proxy\Entities\Traffic;

class TrafficFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Traffic::class;

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

