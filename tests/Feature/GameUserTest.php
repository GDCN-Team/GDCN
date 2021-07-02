<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use App\Models\GameAccountBlock;
use App\Models\GameAccountFriend;
use App\Models\GameAccountPermissionAssign;
use App\Models\GameAccountPermissionGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class GameUserTest
 * @package Tests\Feature
 */
class GameUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_info_guest(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();
        $request = $this->post(
            route('game.user.info'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'targetAccountID' => $account->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}");
    }

    public function test_info_view_self(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();
        $request = $this->post(
            route('game.user.info'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $account->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}");
        $request->assertSee("38:");
        $request->assertSee("39:");
        $request->assertSee("40:");
    }

    public function test_info_from_other_account(): void
    {
        $accounts = GameAccount::factory()->count(2)->create();
        $request = $this->post(
            route('game.user.info'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $accounts[1]->id,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $accounts[0]->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$accounts[0]->name}");
    }

    public function test_search_use_id(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        $request = $this->post(
            route('game.user.search'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'str' => $account->user->id,
                'total' => 0,
                'page' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}");
    }

    public function test_search_part_name(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        $request = $this->post(
            route('game.user.search'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'str' => $account->name[1],
                'total' => 0,
                'page' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}");
    }

    public function test_search_full_name(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        $request = $this->post(
            route('game.user.search'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'str' => $account->name,
                'total' => 0,
                'page' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$account->name}");
    }

    public function test_request_access(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        $group = new GameAccountPermissionGroup();
        $group->name = 'Test Group';
        $group->mod_level = 1;
        $group->save();

        $assign = new GameAccountPermissionAssign();
        $assign->account = $account->id;
        $assign->group = $group->id;
        $assign->save();

        // Normal Mod

        $request = $this->post(
            route('game.user.request.access'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        self::assertEquals(
            '1',
            $request->getContent()
        );

        // Elder Mod

        $group->mod_level = 2;
        $group->save();

        $request = $this->post(
            route('game.user.request.access'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        self::assertEquals(
            '2',
            $request->getContent()
        );
    }

    public function test_get_friends_list(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()
            ->count(2)
            ->create();

        $friend = new GameAccountFriend();
        $friend->account = $accounts[0]->id;
        $friend->target_account = $accounts[1]->id;
        $friend->save();

        $request = $this->post(
            route('game.user.list'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'type' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$accounts[1]->name}:2:{$accounts[1]->user->id}");
    }

    public function test_get_block_list(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()->count(2)->create();

        $block = new GameAccountBlock();
        $block->account = $accounts[0]->id;
        $block->target_account = $accounts[1]->id;
        $block->save();

        $request = $this->post(
            route('game.user.list'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'type' => 1,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$accounts[1]->name}:2:{$accounts[1]->user->id}");
    }
}
