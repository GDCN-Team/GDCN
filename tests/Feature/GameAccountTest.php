<?php

namespace Tests\Feature;

use App\Exceptions\Game\InvalidArgumentException;
use App\Models\GameAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Tests\TestCase;

/**
 * Class GameAccountTest
 * @package Tests\Feature
 */
class GameAccountTest extends TestCase
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
            ])->dump();

        $request->assertOk();
        Event::assertDispatched(Registered::class);
        $this->assertDatabaseHas(
            'game_accounts',
            [
                'name' => $name,
                'email' => $email
            ])->dump();
    }

    public function test_send_verify_email(): void
    {
        Notification::fake();
        Notification::assertNothingSent();

        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();
        $account->sendEmailVerificationNotification();

        Notification::assertSentTo([$account], VerifyEmail::class);
    }

    public function test_empty_udid_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var GameAccount $GameAccount */
        $GameAccount = GameAccount::factory()->create();
        $GameAccount->resolveUser();
    }

    public function test_verify(): void
    {
        $account = GameAccount::factory()->createOne();
        $url = call_user_func(VerifyEmail::$createUrlCallback, $account);

        $this->get($url)
            ->assertRedirect();

        $this->assertEquals(Lang::get('GameAccountEmailVerify.success'), Inertia::getShared('notices')[0]['message']);
    }

    public function test_login(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();
        $account->markEmailAsVerified();

        $request = $this->post(
            route('game.account.login'),
            [
                'userName' => $account->name,
                'password' => 123456,
                'udid' => 'S' . mt_rand(),
                'secret' => 'Wmfv3899gc9'
            ])->dump();

        $request->assertOk();
        self::assertEquals("{$account->id},{$account->user->id}", $request->getContent());
    }
}
