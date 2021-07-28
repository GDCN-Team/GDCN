<?php

namespace Tests\Feature\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Level\Daily;
use App\Models\Game\Level\Weekly;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use function route;

/**
 * Class DailyTest
 * @package Tests\Feature
 */
class DailyTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        Storage::fake('oss');

        /** @var Daily $daily */
        $daily = Daily::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $time = Carbon::rawParse('tomorrow')->diffInSeconds();

        $request->assertSee("$daily->id|$time");
    }

    public function test_get_use_account(): void
    {
        Storage::fake('oss');

        /** @var Account $account */
        $account = Account::factory()->create();

        /** @var Daily $daily */
        $daily = Daily::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $time = Carbon::rawParse('tomorrow')->diffInSeconds();
        $request->assertSee("$daily->id|$time");
    }

    public function test_get_weekly(): void
    {
        Storage::fake('oss');

        /** @var Weekly $weekly */
        $weekly = Weekly::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $time = Carbon::rawParse('next monday')->diffInSeconds();

        $request->assertSee("$weekly->id|$time");
    }

    public function test_get_weekly_use_account(): void
    {
        Storage::fake('oss');

        /** @var Account $account */
        $account = Account::factory()->create();

        /** @var Weekly $weekly */
        $weekly = Weekly::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $time = Carbon::rawParse('next monday')->diffInSeconds();

        $request->assertSee("$weekly->id|$time");
    }
}
