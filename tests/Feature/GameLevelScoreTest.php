<?php

namespace Tests\Feature;

use App\Http\Controllers\Game\HashesController;
use App\Models\GameAccount;
use App\Models\GameAccountFriend;
use App\Models\GameLevelScore;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class GameLevelScoreTest
 * @package Tests\Feature
 */
class GameLevelScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_friends(): void
    {
        /** @var GameAccountFriend $friend */
        $friend = GameAccountFriend::factory()->create();

        /** @var GameLevelScore $score */
        $score = GameLevelScore::factory()
            ->state(['account' => $friend->target_account])
            ->create();

        try {
            $hash = app(HashesController::class);
            $rs = Str::random();

            $request = $this->post(
                route('game.level.score.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $friend->account,
                    'gjp' => 'AgUGBgMF',
                    'levelID' => $score->level,
                    'percent' => 0,
                    'secret' => 'Wmfd2893gb7',
                    'type' => 0,
                    's1' => 8354,
                    's2' => 3991,
                    's3' => 4085,
                    's4' => 1482,
                    's5' => random_int(1000, 10000),
                    's6' => 'AA==',
                    's7' => $rs,
                    's8' => 0,
                    's9' => 5819,
                    's10' => 0,
                    'chk' => $hash->generateUploadLevelScoreChk($friend->account, $score->level, 0, 0, 0, 1482, 4, 0, 0, $rs, true)
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = GameAccount::whereId($friend->target_account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}:2:{$account->user->id}");
    }

    public function test_upload(): void
    {
        /** @var GameLevelScore $score */
        $score = GameLevelScore::factory()->create();

        try {
            $hash = app(HashesController::class);
            $rs = Str::random();

            $request = $this->post(
                route('game.level.score.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $score->account,
                    'gjp' => 'AgUGBgMF',
                    'levelID' => $score->level,
                    'percent' => 98,
                    'secret' => 'Wmfd2893gb7',
                    'type' => 1,
                    's1' => 8354,
                    's2' => 3991,
                    's3' => 4085,
                    's4' => 1482,
                    's5' => random_int(1000, 10000),
                    's6' => 'AA==',
                    's7' => $rs,
                    's8' => 0,
                    's9' => 5819,
                    's10' => 0,
                    'chk' => $hash->generateUploadLevelScoreChk($score->account, $score->level, 98, 0, 0, 1482, 4, 0, 0, $rs, true)
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = GameAccount::whereId($score->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}:2:{$account->user->id}");
        $this->assertDatabaseHas(
            'game_level_scores',
            [
                'account' => $account->id,
                'percent' => 98
            ]
        );
    }

    public function test_get_top(): void
    {
        /** @var GameLevelScore $score */
        $score = GameLevelScore::factory()->create();

        try {
            $hash = app(HashesController::class);
            $rs = Str::random();

            $request = $this->post(
                route('game.level.score.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $score->account,
                    'gjp' => 'AgUGBgMF',
                    'levelID' => $score->level,
                    'percent' => 0,
                    'secret' => 'Wmfd2893gb7',
                    'type' => 1,
                    's1' => 8354,
                    's2' => 3991,
                    's3' => 4085,
                    's4' => 1482,
                    's5' => random_int(1000, 10000),
                    's6' => 'AA==',
                    's7' => $rs,
                    's8' => 0,
                    's9' => 5819,
                    's10' => 0,
                    'chk' => $hash->generateUploadLevelScoreChk($score->account, $score->level, 0, 0, 0, 1482, 4, 0, 0, $rs, true)
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = GameAccount::whereId($score->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}:2:{$account->user->id}");
    }

    public function test_get_week(): void
    {
        /** @var GameLevelScore $score */
        $score = GameLevelScore::factory()->create();

        try {
            $hash = app(HashesController::class);
            $rs = Str::random();

            $request = $this->post(
                route('game.level.score.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $score->account,
                    'gjp' => 'AgUGBgMF',
                    'levelID' => $score->level,
                    'percent' => 0,
                    'secret' => 'Wmfd2893gb7',
                    'type' => 2,
                    's1' => 8354,
                    's2' => 3991,
                    's3' => 4085,
                    's4' => 1482,
                    's5' => random_int(1000, 10000),
                    's6' => 'AA==',
                    's7' => $rs,
                    's8' => 0,
                    's9' => 5819,
                    's10' => 0,
                    'chk' => $hash->generateUploadLevelScoreChk($score->account, $score->level, 0, 0, 0, 1482, 4, 0, 0, $rs, true)
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = GameAccount::whereId($score->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}:2:{$account->user->id}");
    }
}
