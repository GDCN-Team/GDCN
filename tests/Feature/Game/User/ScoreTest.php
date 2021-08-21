<?php

namespace Tests\Feature\Game\User;

use App\Models\Game\Account;
use App\Models\Game\Account\Friend;
use App\Models\Game\User;
use App\Models\Game\UserScore;
use GDCN\Hash\Components\UpdateUserScoreSeed2;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use function route;

/**
 * Class ScoreTest
 * @package Tests\Feature
 */
class ScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload(): void
    {
        $request = $this->post(
            route('game.user.score.update'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'uuid' => 0,
                'udid' => 'S' . mt_rand(),
                'userName' => 'test_user',
                'stars' => 0,
                'demons' => 0,
                'diamonds' => 0,
                'icon' => 0,
                'color1' => 0,
                'color2' => 3,
                'iconType' => 0,
                'coins' => 0,
                'userCoins' => 0,
                'special' => 0,
                'secret' => 'Wmfd2893gb7',
                'accIcon' => 0,
                'accShip' => 0,
                'accBall' => 0,
                'accBird' => 0,
                'accDart' => 0,
                'accRobot' => 0,
                'accGlow' => 0,
                'accSpider' => 0,
                'accExplosion' => 0,
                'seed' => Str::random(),
                'seed2' => app(UpdateUserScoreSeed2::class)->encode(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas('game_user_scores', [
            'user' => $request->getContent()
        ]);
    }

    public function test_upload_use_account(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        $request = $this->post(
            route('game.user.score.update'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'userName' => $account->name,
                'stars' => 0,
                'demons' => 0,
                'diamonds' => 0,
                'icon' => 0,
                'color1' => 0,
                'color2' => 3,
                'iconType' => 0,
                'coins' => 0,
                'userCoins' => 0,
                'special' => 0,
                'secret' => 'Wmfd2893gb7',
                'accIcon' => 0,
                'accShip' => 0,
                'accBall' => 0,
                'accBird' => 0,
                'accDart' => 0,
                'accRobot' => 0,
                'accGlow' => 0,
                'accSpider' => 0,
                'accExplosion' => 0,
                'seed' => Str::random(),
                'seed2' => app(UpdateUserScoreSeed2::class)->encode($account->id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
            ]
        );

        $request->assertOk();
        self::assertEquals(
            $account->id,
            $request->getContent()
        );

        $this->assertDatabaseHas('game_user_scores', [
            'user' => $request->getContent()
        ]);
    }

    public function test_get_top(): void
    {
        /** @var UserScore $score */
        $score = UserScore::factory()->create();

        /** @var User $user */
        $user = $score->getRelationValue('user');

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => $user?->udid,
                'uuid' => $user?->id,
                'type' => 'top',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("7:{$score->getRelationValue('user')->uuid}");
    }

    public function test_get_top_use_account(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        UserScore::factory()
            ->state(['user' => $account->user->id])
            ->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'type' => 'top',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("16:$account->id");
    }

    public function test_get_friends(): void
    {
        /** @var Friend $friend */
        $friend = Friend::factory()->create();

        try {
            $account = Account::whereId($friend->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        UserScore::factory()
            ->state(['user' => $account->user->id])
            ->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friend->target_account,
                'gjp' => 'AgUGBgMF',
                'type' => 'friends',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("16:$friend->account");
    }

    public function test_get_relative(): void
    {
        /** @var UserScore $score */
        $score = UserScore::factory()->create();

        /** @var User $user */
        $user = $score->getRelationValue('user');

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => $user?->udid,
                'uuid' => 0,
                'type' => 'relative',
                'count' => 50,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("7:{$score->getRelationValue('user')->uuid}");
    }

    public function test_get_relative_use_account(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        /** @var UserScore $score */
        $score = UserScore::factory()
            ->state(['user' => $account->user->id])
            ->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $score->getRelationValue('user')->account->id,
                'gjp' => 'AgUGBgMF',
                'type' => 'relative',
                'count' => 50,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("7:{$score->getRelationValue('user')->uuid}");
    }

    public function test_get_creators(): void
    {
        /** @var UserScore $score */
        $score = UserScore::factory()->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => $score->getRelationValue('user')?->udid,
                'uuid' => $score->getRelationValue('user')?->id,
                'type' => 'creators',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("7:{$score->getRelationValue('user')->uuid}");
    }

    public function test_get_creators_use_account(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        /** @var UserScore $score */
        $score = UserScore::factory()
            ->state(['user' => $account->user->id])
            ->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $score->getRelationValue('user')->account->id,
                'gjp' => 'AgUGBgMF',
                'type' => 'creators',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("7:{$score->getRelationValue('user')->uuid}");
    }
}
