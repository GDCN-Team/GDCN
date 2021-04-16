<?php

namespace Database\Factories;

use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;
use Base64Url\Base64Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameAccountFriendRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccountFriendRequest::class;

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
            'comment' => Base64Url::encode($this->faker->word, true)
        ];
    }
}
