<?php

namespace Tests\Feature\Game;

use App\Exceptions\Game\InvalidArgumentException;
use App\Models\Game\Account;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Tests\TestCase;
use function route;

/**
 * Class AccountTest
 * @package Tests\Feature
 */
class AccountTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_register(): void
    {
        Event::fake();
        $name = $this->faker->firstName;
        $email = $this->faker->safeEmail;

        $request = $this->post(
            route('game.account.register'),
            [
                'userName' => $name,
                'password' => 123456,
                'email' => $email,
                'secret' => 'Wmfv3899gc9'
            ]
        );

        $request->dump();
        $request->assertOk();
        Event::assertDispatched(Registered::class);
        $this->assertDatabaseHas(
            'game_accounts',
            [
                'name' => $name,
                'email' => $email
            ]
        );
    }

    public function test_send_verify_email(): void
    {
        Notification::fake();
        Notification::assertNothingSent();

        /** @var Account $account */
        $account = Account::factory()->create();
        $account->sendEmailVerificationNotification();

        Notification::assertSentTo([$account], VerifyEmail::class);
    }

    public function test_empty_udid_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var Account $GameAccount */
        $GameAccount = Account::factory()->create();
        $GameAccount->resolveUser();
    }

    public function test_verify(): void
    {
        $account = Account::factory()->createOne();
        $url = call_user_func(VerifyEmail::$createUrlCallback, $account);

        $this->get($url)
            ->assertRedirect();

        $this->assertEquals(Lang::get('GameAccountEmailVerify.success'), Inertia::getShared('notices')[0]['message']);
    }

    public function test_login(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();
        $account->markEmailAsVerified();

        $request = $this->post(
            route('game.account.login'),
            [
                'userName' => $account->name,
                'password' => 123456,
                'udid' => 'S' . mt_rand(),
                'secret' => 'Wmfv3899gc9'
            ]
        );

        $request->dump();
        $request->assertOk();
        self::assertEquals("{$account->id},{$account->user->id}", $request->getContent());
    }
}
