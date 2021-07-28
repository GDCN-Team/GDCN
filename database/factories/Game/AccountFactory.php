<?php

namespace Database\Factories\Game;

use App\Models\Game\Account;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use function now;

/**
 * Class AccountFactory
 * @package Database\Factories
 */
class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * @return AccountFactory
     */
    public function configure(): AccountFactory
    {
        return $this->afterCreating(function (Account $account) {
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
            'password' => Hash::make(123456),
            'email_verified_at' => now()
        ];
    }
}
