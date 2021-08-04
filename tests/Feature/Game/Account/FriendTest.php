<?php

namespace Tests\Feature\Game\Account;

use App\Enums\Game\ResponseCode;
use App\Models\Game\Account\Friend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

class FriendTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete(): void
    {
        /** @var Friend $friend */
        $friend = Friend::factory()->create();

        $request = $this->post(
            route('game.account.friend.remove'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friend->target_account,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $friend->account,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEqualsIgnoringCase(
            ResponseCode::OK,
            $request->getContent()
        );

        $this->assertDeleted(
            $friend->getTable(),
            $friend->attributesToArray()
        );
    }
}
