<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class GameAccountSettingTest
 * @package Tests\Feature
 */
class GameAccountSettingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_update(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

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
            ])->dump();

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
            ])->dump();
    }
}
