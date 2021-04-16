<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use App\Models\GameDailyLevel;
use App\Models\GameWeeklyLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Class GameLevelDailyTest
 * @package Tests\Feature
 */
class GameLevelDailyTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        /** @var GameDailyLevel $daily */
        $daily = GameDailyLevel::factory()->create();

        $request = $this->post(
            route('game.daily.level.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'secret' => 'Wmfd2893gb7',
                'weekly' => false
            ]
        );

        $request->assertOk();
        $time = Carbon::rawParse('tomorrow')->diffInSeconds();

        $request->assertSee("{$daily->id}|{$time}");
    }

    public function test_get_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameDailyLevel $daily */
        $daily = GameDailyLevel::factory()->create();

        $request = $this->post(
            route('game.daily.level.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7',
                'weekly' => false
            ]
        );

        $request->assertOk();
        $time = Carbon::rawParse('tomorrow')->diffInSeconds();

        $request->assertSee("{$daily->id}|{$time}");
    }

    public function test_get_weekly(): void
    {
        /** @var GameWeeklyLevel $weekly */
        $weekly = GameWeeklyLevel::factory()->create();

        $request = $this->post(
            route('game.daily.level.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'secret' => 'Wmfd2893gb7',
                'weekly' => true
            ]
        );

        $request->assertOk();
        $time = Carbon::rawParse('next monday')->diffInSeconds();

        $request->assertSee("{$weekly->id}|{$time}");
    }

    public function test_get_weekly_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameWeeklyLevel $weekly */
        $weekly = GameWeeklyLevel::factory()->create();

        $request = $this->post(
            route('game.daily.level.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7',
                'weekly' => true
            ]
        );

        $request->assertOk();
        $time = Carbon::rawParse('next monday')->diffInSeconds();

        $request->assertSee("{$weekly->id}|{$time}");
    }
}
