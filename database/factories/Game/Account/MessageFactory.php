<?php

namespace Database\Factories\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account' => Account::factory(),
            'to_account' => Account::factory(),
            'subject' => Base64Url::encode($this->faker->word, true),
            'body' => Base64Url::encode($this->faker->word, true)
        ];
    }
}
