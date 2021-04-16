<?php

namespace Database\Factories;

use App\Models\GameAccount;
use App\Models\GameAccountMessage;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameAccountMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccountMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => GameAccount::factory(),
            'to_account' => GameAccount::factory(),
            'subject' => Base64Url::encode($this->faker->word, true),
            'body' => Base64Url::encode($this->faker->word, true)
        ];
    }
}
