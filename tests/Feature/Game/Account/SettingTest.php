<?php

namespace Tests\Feature\Game\Account;

use App\Models\Game\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function route;

/**
 * Class SettingTest
 * @package Tests\Feature
 */
class SettingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_update(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        $yt = $this->faker->domainWord;
        $twitter = $this->faker->domainWord;
        $twitch = $this->faker->domainWord;

        $request = $this->post(
            route('game.account.setting.update'),
            [
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'mS' => 0,
                'frS' => 1,
                'cS' => 2,
                'yt' => $yt,
                'twitter' => $twitter,
                'twitch' => $twitch,
                'secret' => 'Wmfv3899gc9'
            ]
        );

        $request->dump();
        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_settings',
            [
                'account' => $account->id,
                'message_state' => 0,
                'friend_request_state' => 1,
                'comment_history_state' => 2,
                'youtube' => $yt,
                'twitter' => $twitter,
                'twitch' => $twitch
            ]
        );
    }
}
