<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use App\Models\GameAccountPermissionGroup;
use App\Models\GameLevel;
use GDCN\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GameLevelRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggest(): void
    {
        /** @var GameAccountPermissionGroup $group */
        $group = GameAccountPermissionGroup::factory()
            ->create(['mod_level' => 2]);

        /** @var GameAccount $account */
        $account = GameAccount::factory()
            ->create();

        /** @var GameLevel $level */
        $level = GameLevel::factory()
            ->create();

        config([
            'game.feature.auto_rate.suggest.enabled' => true,
            'game.feature.auto_rate.suggest.least_suggest' => 1
        ]);

        $account->assignGroup($group->name);
        $request = $this->post(
            route('game.level.rating.suggest'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'levelID' => $level->id,
                'stars' => 8,
                'feature' => false,
                'secret' => 'Wmfp3879gc3'
            ])->dump();

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'stars' => 8
            ])->dump();
    }

    public function test_rate(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()
            ->create();

        /** @var GameLevel $level */
        $level = GameLevel::factory()
            ->create();

        config([
            'game.feature.auto_rate.rate.enabled' => true,
            'game.feature.auto_rate.rate.mod_only' => false,
            'game.feature.auto_rate.rate.least_suggest' => 1
        ]);

        $udid = 'S' . mt_rand();
        $rs = Str::random();

        $request = $this->post(
            route('game.level.rating.rate'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'udid' => $udid,
                'uuid' => 0,
                'levelID' => $level->id,
                'stars' => 8,
                'secret' => 'Wmfd2893gb7',
                'rs' => $rs,
                'chk' => Hash::generateChkForRate($level->id, 8, $rs, $account->id, $udid, 0, true)
            ])->dump();

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'difficulty' => 50
            ])->dump();
    }

    public function test_demon(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()
            ->create();

        /** @var GameLevel $level */
        $level = GameLevel::factory()
            ->create();

        $level->rate(10);

        config([
            'game.feature.auto_rate.demon.enabled' => true,
            'game.feature.auto_rate.demon.mod_only' => false,
            'game.feature.auto_rate.demon.least_suggest' => 1
        ]);

        $request = $this->post(
            route('game.level.rating.demon'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'levelID' => $level->id,
                'rating' => 1,
                'secret' => 'Wmfp3879gc3'
            ])->dump();

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'demon_difficulty' => 3
            ])->dump();
    }
}
