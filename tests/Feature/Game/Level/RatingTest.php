<?php

namespace Tests\Feature\Game\Level;

use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\User;
use GDCN\Hash\Components\RateChk;
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
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        $groupID = $account->permission_group()
            ->create([
                'name' => 'Owner',
                'mod_level' => 2
            ])->value('id');

        Account\Permission\Assign::insert([
            'account' => $account->id,
            'group' => $groupID
        ]);

        Account\Permission\FlagAssign::insert([
            'group' => $groupID,
            'flag' => Account\Permission\Flag::insertGetId([
                'name' => 'CREATE_LEVEL_SUGGESTION'
            ])
        ]);

        /** @var Level $level */
        $level = Level::factory()
            ->create();

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

        $request->assertOk();
        $this->assertDatabaseHas(
            Level\RatingSuggestion::class,
            [
                'level' => $level->id,
                'rating' => 8
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
                'chk' => app(RateChk::class)->encode($level->id, 8, $rs, $account->id, $user->udid, $user->id)
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'difficulty' => 50
            ]
        );
    }

    public function test_auto_rate_change()
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
            ->create();

        $level->rating()
            ->create([
                'stars' => 8,
                'difficulty' => 50,
                'featured_score' => 0,
                'epic' => false,
                'coin_verified' => false,
                'auto' => false,
                'demon' => false,
                'demon_difficulty' => 0
            ]);

        config([
            'game.feature.auto_rate.rate.enabled' => true,
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
                'stars' => 5,
                'secret' => 'Wmfd2893gb7',
                'rs' => $rs,
                'chk' => Str::random() // not work, use random string instead
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'stars' => 8,
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

        $level->rating()
            ->create([
                'stars' => 10,
                'difficulty' => 60,
                'featured_score' => 0,
                'epic' => false,
                'coin_verified' => false,
                'auto' => false,
                'demon' => true,
                'demon_difficulty' => 0
            ]);

        config([
            'game.feature.auto_rate.demon.enabled' => true,
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

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'demon_difficulty' => 3
            ]
        );
    }

    public function test_auto_rate_demon_change()
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
            ->create();

        $level->rating()
            ->create([
                'stars' => 10,
                'difficulty' => 60,
                'featured_score' => 0,
                'epic' => false,
                'coin_verified' => false,
                'auto' => false,
                'demon' => true,
                'demon_difficulty' => 4
            ]);

        config([
            'game.feature.auto_rate.demon.enabled' => true,
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

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_ratings',
            [
                'level' => $level->id,
                'demon_difficulty' => 4
            ]
        );
    }
}
