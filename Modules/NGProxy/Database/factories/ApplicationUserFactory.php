<?php

namespace Modules\NGProxy\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\NGProxy\Entities\ApplicationUser;

class ApplicationUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicationUser::class;

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

