<?php

namespace Tests\Feature\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Models\Game\Account;
use App\Models\Game\Account\Block;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

class BlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_block(): void
    {
        /** @var Account[] $accounts */
        $accounts = Account::factory()
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.block.block'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $accounts[1]->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );


        self::assertEqualsIgnoringCase(
            ResponseCode::OK,
            $request->getContent()
        );

        $this->assertDatabaseHas(
            'game_account_blocks',
            [
                'account' => $accounts[0]->id,
                'target_account' => $accounts[1]->id
            ]
        );
    }

    public function test_unblock(): void
    {
        /** @var Block $block */
        $block = Block::factory()
            ->create();

        $request = $this->post(
            route('game.account.block.unblock'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => 0,
                'accountID' => $block->account,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $block->target_account,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEqualsIgnoringCase(
            ResponseCode::OK,
            $request->getContent()
        );

        $this->assertDeleted($block);
    }
}
