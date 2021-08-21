<?php

namespace Tests\Feature\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Account\Friend;
use App\Models\Game\Level\Score;
use Exception;
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

    public function test_get_friends(): void
    {
        /** @var Friend $friend */
        $friend = Friend::factory()->create();

        /** @var Score $score */
        $score = Score::factory()
            ->state(['account' => $friend->target_account])
            ->create();

        try {
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
                    'type' => '0',
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
                    'chk' => Str::random() // Current not work, use random string instead
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = Account::whereId($friend->target_account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->assertOk();
        $request->assertSee("1:$account->name:2:{$account->user->id}");
    }

    public function test_upload(): void
    {
        /** @var Score $score */
        $score = Score::factory()->create();

        try {
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
                    'type' => '1',
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
                    'chk' => Str::random()
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = Account::whereId($score->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->assertOk();
        $request->assertSee("1:$account->name:2:{$account->user->id}");
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
        /** @var Score $score */
        $score = Score::factory()->create();

        try {
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
                    'type' => '1',
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
                    'chk' => Str::random()
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = Account::whereId($score->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->assertOk();
        $request->assertSee("1:$account->name:2:{$account->user->id}");
    }

    public function test_get_week(): void
    {
        /** @var Score $score */
        $score = Score::factory()->create();

        try {
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
                    'type' => '2',
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
                    'chk' => Str::random()
                ]
            );
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        try {
            $account = Account::whereId($score->account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $request->assertOk();
        $request->assertSee("1:$account->name:2:{$account->user->id}");
    }
}
