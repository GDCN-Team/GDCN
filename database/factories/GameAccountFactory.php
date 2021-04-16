<?php

namespace Database\Factories;

use App\Models\GameAccount;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * Class GameAccountFactory
 * @package Database\Factories
 */
class GameAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccount::class;

    /**
     * @return GameAccountFactory
     */
    public function configure(): GameAccountFactory
    {
        return $this->afterCreating(function (GameAccount $account) {
            try {
                $udid = 'S' . mt_rand();
                $account->resolveUser($udid);
            } catch (Exception $e) {

            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'email' => $this->faker->safeEmail,
            'password' => Hash::make('123456'),
            'email_verified_at' => now()
        ];
    }
}
