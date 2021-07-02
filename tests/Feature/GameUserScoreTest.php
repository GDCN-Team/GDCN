<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use App\Models\GameAccountFriend;
use App\Models\GameUserScore;
use Base64Url\Base64Url;
use GDCN\XORCipher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class GameUserScoreTest
 * @package Tests\Feature
 */
class GameUserScoreTest extends TestCase
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
                'seed2' => 'AFAKDwIAVAJUBl1UVFUFDgILA1IODQUHAAsDU1EECwQDDwUMDVEBCQ=='
            ])->dump();

        $request->assertOk();
        $this->assertDatabaseHas('game_user_scores', [
            'user' => $request->getContent()
        ]);
    }

    public function test_upload_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

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
                'seed2' => Base64Url::encode(
                    XORCipher::cipher(
                        sha1("{$account->id}0000000000000000xI35fsAapCRg"),
                        85271), true)
            ])->dump();

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
        /** @var GameUserScore $score */
        $score = GameUserScore::factory()->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'type' => 'top',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ])->dump();

        $request->assertOk();
        $request->assertSee("7:{$score->owner->uuid}");
    }

    public function test_get_top_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        GameUserScore::factory()
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
            ])->dump();

        $request->assertOk();
        $request->assertSee("16:{$account->id}");
    }

    public function test_get_friends(): void
    {
        /** @var GameAccountFriend $friend */
        $friend = GameAccountFriend::factory()->create();

        try {
            $account = GameAccount::whereId($friend->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        GameUserScore::factory()
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
            ])->dump();

        $request->assertOk();
        $request->assertSee("16:{$friend->account}");
    }

    public function test_get_relative(): void
    {
        /** @var GameUserScore $score */
        $score = GameUserScore::factory()->create();


        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'type' => 'relative',
                'count' => 50,
                'secret' => 'Wmfd2893gb7'
            ])->dump();

        $request->assertOk();
        $request->assertSee("7:{$score->owner->uuid}");
    }

    public function test_get_relative_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameUserScore $score */
        $score = GameUserScore::factory()
            ->state(['user' => $account->user->id])
            ->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $score->owner->account->id,
                'gjp' => 'AgUGBgMF',
                'type' => 'relative',
                'count' => 50,
                'secret' => 'Wmfd2893gb7'
            ])->dump();

        $request->assertOk();
        $request->assertSee("7:{$score->owner->uuid}");
    }

    public function test_get_creators(): void
    {
        /** @var GameUserScore $score */
        $score = GameUserScore::factory()->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'type' => 'creators',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ])->dump();

        $request->assertOk();
        $request->assertSee("7:{$score->owner->uuid}");
    }

    public function test_get_creators_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameUserScore $score */
        $score = GameUserScore::factory()
            ->state(['user' => $account->user->id])
            ->create();

        $request = $this->post(
            route('game.score.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $score->owner->account->id,
                'gjp' => 'AgUGBgMF',
                'type' => 'creators',
                'count' => 100,
                'secret' => 'Wmfd2893gb7'
            ])->dump();

        $request->assertOk();
        $request->assertSee("7:{$score->owner->uuid}");
    }
}
