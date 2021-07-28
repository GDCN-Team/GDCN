<?php

namespace Tests\Feature\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Account\Permission\Group;
use App\Models\Game\Level;
use App\Models\Game\User;
use App\Services\Game\Level\RatingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use function config;
use function route;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggest(): void
    {
        /** @var Group $group */
        $group = Group::factory()
            ->create(['mod_level' => 2]);

        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
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
            ]
        );

        $request->dump();
        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'stars' => 8
            ]
        );
    }

    public function test_rate(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
            ->create();

        config([
            'game.feature.auto_rate.rate.enabled' => true,
            'game.feature.auto_rate.rate.mod_only' => false,
            'game.feature.auto_rate.rate.least_suggest' => 1
        ]);

        /** @var User $user */
        $user = User::factory()->createOne();
        $rs = Str::random();

        $request = $this->post(
            route('game.level.rating.rate'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'udid' => $user->udid,
                'uuid' => $user->id,
                'levelID' => $level->id,
                'stars' => 8,
                'secret' => 'Wmfd2893gb7',
                'rs' => $rs,
                'chk' => Str::random() // not work, use random string instead
            ]
        );

        $request->dump();
        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'difficulty' => 50
            ]
        );
    }

    public function test_demon(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
            ->create();

        $service = app(RatingService::class);
        $service->rate($level, 10);

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
            ]
        );

        $request->dump();
        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'demon_difficulty' => 3
            ]
        );
    }
}
