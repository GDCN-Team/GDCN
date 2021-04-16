<?php

namespace Tests\Feature;

use App\Enums\ResponseCode;
use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;
use App\Models\GameAccountSetting;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameAccountFriendRequestTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_send(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.friend.request.send'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'toAccountID' => $accounts[1]->id,
                'comment' => Base64Url::encode($this->faker->word, true),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_friend_requests',
            [
                'account' => $accounts[0]->id,
                'to_account' => $accounts[1]->id
            ]
        );
    }

    public function test_send_use_none_policy(): void
    {
        /** @var GameAccount[] $accounts */
        $accounts = GameAccount::factory()
            ->count(2)
            ->hasSetting([
                'message_state' => 0,
                'friend_request_state' => 1,
                'comment_history_state' => 0
            ])->create();

        /** @var GameAccountSetting $setting */
        $setting = $accounts[1]->setting()->firstOrNew();
        $setting->message_state = 0;
        $setting->friend_request_state = 1;
        $setting->comment_history_state = 0;
        $setting->save();

        $request = $this->post(
            route('game.account.friend.request.send'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $accounts[0]->id,
                'gjp' => 'AgUGBgMF',
                'toAccountID' => $accounts[1]->id,
                'comment' => Base64Url::encode($this->faker->word, true),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEqualsIgnoringCase(ResponseCode::FAILED, $request->getContent());
    }

    public function test_delete_single(): void
    {
        /** @var GameAccountFriendRequest $friendRequest */
        $friendRequest = GameAccountFriendRequest::factory()->create();

        $request = $this->post(
            route('game.account.friend.request.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequest->account,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $friendRequest->to_account,
                'isSender' => true,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDeleted(
            $friendRequest->getTable(),
            $friendRequest->attributesToArray()
        );
    }

    public function test_delete_single_from_other(): void
    {
        /** @var GameAccountFriendRequest $friendRequest */
        $friendRequest = GameAccountFriendRequest::factory()->create();

        $request = $this->post(
            route('game.account.friend.request.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequest->to_account,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $friendRequest->account,
                'isSender' => false,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDeleted(
            $friendRequest->getTable(),
            $friendRequest->attributesToArray()
        );
    }

    public function test_delete_multi(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();
        $accountID = $account->id;

        /** @var GameAccountFriendRequest[] $friendRequests */
        $friendRequests = GameAccountFriendRequest::factory()
            ->state(['account' => $accountID])
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.friend.request.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequests[0]->account,
                'gjp' => 'AgUGBgMF',
                'isSender' => true,
                'secret' => 'Wmfd2893gb7',
                'accounts' => "{$friendRequests[0]->to_account},{$friendRequests[1]->to_account}"
            ]
        );

        $request->assertOk();
        foreach ($friendRequests as $friendRequest) {
            $this->assertDeleted(
                $friendRequest->getTable(),
                $friendRequest->attributesToArray()
            );
        }
    }

    public function test_delete_multi_from_other(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameAccountFriendRequest[] $friendRequests */
        $friendRequests = GameAccountFriendRequest::factory()
            ->state(['to_account' => $account->id])
            ->count(2)
            ->create();

        $request = $this->post(
            route('game.account.friend.request.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequests[0]->to_account,
                'gjp' => 'AgUGBgMF',
                'isSender' => false,
                'secret' => 'Wmfd2893gb7',
                'accounts' => "{$friendRequests[0]->account},{$friendRequests[1]->account}"
            ]
        );

        $request->assertOk();
        foreach ($friendRequests as $friendRequest) {
            $this->assertDeleted(
                $friendRequest->getTable(),
                $friendRequest->attributesToArray()
            );
        }
    }

    public function test_get(): void
    {
        /** @var GameAccountFriendRequest $friendRequest */
        $friendRequest = GameAccountFriendRequest::factory()->create();

        $request = $this->post(
            route('game.account.friend.request.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequest->to_account,
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

    public function test_get_with_page(): void
    {
        $perPage = config('game.perPage', 10);

        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        GameAccountFriendRequest::factory()
            ->state(['to_account' => $account->id])
            ->count(++$perPage)
            ->create();

        $request = $this->post(
            route('game.account.friend.request.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'page' => 1,
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
        /** @var GameAccountFriendRequest $friendRequest */
        $friendRequest = GameAccountFriendRequest::factory()->create();

        $request = $this->post(
            route('game.account.friend.request.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequest->account,
                'gjp' => 'AgUGBgMF',
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'getSent' => true
            ]
        );

        $request->assertOk();
        $request->assertSee("32:{$friendRequest->id}:35:{$friendRequest->comment}");
    }

    public function test_read(): void
    {
        /** @var GameAccountFriendRequest $friendRequest */
        $friendRequest = GameAccountFriendRequest::factory()->create();

        $request = $this->post(
            route('game.account.friend.request.read'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequest->to_account,
                'gjp' => 'AgUGBgMF',
                'requestID' => $friendRequest->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEquals(
            '1',
            $request->getContent()
        );
    }

    public function test_accept(): void
    {
        /** @var GameAccountFriendRequest $friendRequest */
        $friendRequest = GameAccountFriendRequest::factory()->create();

        $request = $this->post(
            route('game.account.friend.request.accept'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $friendRequest->to_account,
                'gjp' => 'AgUGBgMF',
                'targetAccountID' => $friendRequest->account,
                'requestID' => $friendRequest->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_friends',
            [
                'account' => $friendRequest->account,
                'target_account' => $friendRequest->to_account
            ]
        );
    }
}
