<?php

namespace Tests\Feature;

use App\Enums\Game\ResponseCode;
use App\Models\GameAccount;
use App\Models\GameAccountFriend;
use App\Models\GameAccountMessage;
use App\Models\GameAccountSetting;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameAccountMessageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_upload(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.message.send'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'toAccountID' => $accounts[1]->id,
                'subject' => $this->faker->word,
                'body' => Base64Url::encode($this->faker->word, true),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_messages',
            [
                'account' => $accounts[0]->id,
                'to_account' => $accounts[1]->id
            ]
        );
    }

    public function test_upload_use_friend_policy(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()
            ->count(2)
            ->create();

        /** @var GameAccountSetting $setting */
        $setting = $accounts[1]->setting()->firstOrNew();
        $setting->message_state = 1;
        $setting->friend_request_state = 0;
        $setting->comment_history_state = 0;
        $setting->save();

        $friend = new GameAccountFriend();
        $friend->account = $accounts[0]->id;
        $friend->target_account = $accounts[1]->id;
        $friend->save();

        $request = $this->post(
            route('game.account.message.send'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'toAccountID' => $accounts[1]->id,
                'subject' => $this->faker->word,
                'body' => Base64Url::encode($this->faker->word, true),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_messages',
            [
                'account' => $accounts[0]->id,
                'to_account' => $accounts[1]->id
            ]
        );
    }

    public function test_upload_use_none_policy(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()
            ->count(2)
            ->create();

        /** @var GameAccountSetting $setting */
        $setting = $accounts[1]->setting()->firstOrNew();
        $setting->message_state = 2;
        $setting->friend_request_state = 0;
        $setting->comment_history_state = 0;
        $setting->save();

        $request = $this->post(
            route('game.account.message.send'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'toAccountID' => $accounts[1]->id,
                'subject' => $this->faker->word,
                'body' => Base64Url::encode($this->faker->word, true),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEqualsIgnoringCase(ResponseCode::FAILED, $request->getContent());
    }

    public function test_get(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->to_account,
                'gjp' => 'AgUGBgMF',
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'getSent' => false
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$message->id}:2:{$message->account}");
    }

    public function test_get_with_page(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->to_account,
                'gjp' => 'AgUGBgMF',
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'getSent' => false
            ]
        );

        $request->assertOk();
        $response = $request->getContent();
        $left = explode('#', $response)[0];
        $comments = explode('|', $left);

        self::assertCount(1, $comments);
    }

    public function test_get_sent(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->account,
                'gjp' => 'AgUGBgMF',
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'getSent' => true
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$message->id}:2:{$message->to_account}");
    }

    public function test_delete(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->to_account,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7',
                'isSender' => false,
                'messageID' => $message->id
            ]
        );

        $request->assertOk();
        $this->assertDeleted(
            $message->getTable(),
            $message->attributesToArray()
        );
    }

    public function test_delete_from_other(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->account,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7',
                'isSender' => true,
                'messageID' => $message->id
            ]
        );

        $request->assertOk();
        $this->assertDeleted(
            $message->getTable(),
            $message->attributesToArray()
        );
    }

    public function test_delete_multi(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameAccountMessage[] $messages */
        $messages = GameAccountMessage::factory()
            ->state(['to_account' => $account->id])
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.message.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7',
                'isSender' => true,
                'messages' => "{$messages[0]->id},{$messages[1]->id}"
            ]
        );

        $request->assertOk();
        foreach ($messages as $message) {
            $this->assertDeleted(
                $message->getTable(),
                $message->attributesToArray()
            );
        }
    }

    public function test_delete_multi_from_other(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameAccountMessage[] $messages */
        $messages = GameAccountMessage::factory()
            ->state(['account' => $account->id])
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.message.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'secret' => 'Wmfd2893gb7',
                'isSender' => true,
                'messages' => "{$messages[0]->id},{$messages[1]->id}"
            ]
        );

        $request->assertOk();
        foreach ($messages as $message) {
            $this->assertDeleted(
                $message->getTable(),
                $message->attributesToArray()
            );
        }
    }

    public function test_download(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.download'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->to_account,
                'gjp' => 'AgUGBgMF',
                'messageID' => $message->id,
                'secret' => 'Wmfd2893gb7',
                'isSender' => false
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$message->id}:2:{$message->account}");

        $this->assertDatabaseHas(
            $message->getTable(),
            [
                'id' => $message->id,
                'readed' => true
            ]
        );
    }

    public function test_download_from_other(): void
    {
        /** @var GameAccountMessage $message */
        $message = GameAccountMessage::factory()->create();

        $request = $this->post(
            route('game.account.message.download'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $message->account,
                'gjp' => 'AgUGBgMF',
                'messageID' => $message->id,
                'secret' => 'Wmfd2893gb7',
                'isSender' => true
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$message->id}:2:{$message->to_account}");

        $this->assertDatabaseHas(
            $message->getTable(),
            [
                'id' => $message->id,
                'readed' => true
            ]
        );
    }
}
