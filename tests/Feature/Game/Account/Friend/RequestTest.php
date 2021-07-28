<?php

namespace Tests\Feature\Game\Account\Friend;

use App\Enums\Game\ResponseCode;
use App\Models\Game\Account;
use App\Models\Game\Account\FriendRequest;
use App\Models\Game\Account\Setting;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function config;
use function route;

class RequestTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_send(): void
    {
        /** @var Account[] $accounts */
        $accounts = Account::factory()
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

        $request->dump();
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
        /** @var Account[] $accounts */
        $accounts = Account::factory()
            ->count(2)
            ->hasSetting([
                'message_state' => 0,
                'friend_request_state' => 1,
                'comment_history_state' => 0
            ])->create();

        /** @var Setting $setting */
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

        $request->dump();
        $request->assertOk();
        self::assertEqualsIgnoringCase(ResponseCode::FAILED, $request->getContent());
    }

    public function test_delete_single(): void
    {
        /** @var FriendRequest $friendRequest */
        $friendRequest = FriendRequest::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $this->assertDeleted(
            $friendRequest->getTable(),
            $friendRequest->attributesToArray()
        );
    }

    public function test_delete_single_from_other(): void
    {
        /** @var FriendRequest $friendRequest */
        $friendRequest = FriendRequest::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $this->assertDeleted(
            $friendRequest->getTable(),
            $friendRequest->attributesToArray()
        );
    }

    public function test_delete_multi(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();
        $accountID = $account->id;

        /** @var FriendRequest[] $friendRequests */
        $friendRequests = FriendRequest::factory()
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

        $request->dump();
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
        /** @var Account $account */
        $account = Account::factory()->create();

        /** @var FriendRequest[] $friendRequests */
        $friendRequests = FriendRequest::factory()
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

        $request->dump();
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
        /** @var FriendRequest $friendRequest */
        $friendRequest = FriendRequest::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $response = $request->getContent();
        $left = explode('#', $response)[0];
        $comments = explode('|', $left);

        self::assertCount(1, $comments);
    }

    public function test_get_with_page(): void
    {
        $perPage = config('game.perPage', 10);

        /** @var Account $account */
        $account = Account::factory()->create();

        FriendRequest::factory()
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

        $request->dump();
        $request->assertOk();
        $response = $request->getContent();
        $left = explode('#', $response)[0];
        $comments = explode('|', $left);

        self::assertCount(1, $comments);
    }

    public function test_get_sent(): void
    {
        /** @var FriendRequest $friendRequest */
        $friendRequest = FriendRequest::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $request->assertSee("32:$friendRequest->id:35:$friendRequest->comment");
    }

    public function test_read(): void
    {
        /** @var FriendRequest $friendRequest */
        $friendRequest = FriendRequest::factory()->create();

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

        $request->dump();
        $request->assertOk();
        self::assertEquals(
            '1',
            $request->getContent()
        );
    }

    public function test_accept(): void
    {
        /** @var FriendRequest $friendRequest */
        $friendRequest = FriendRequest::factory()->create();

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

        $request->dump();
        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_friends',
            [
                'account' => $friendRequest->to_account,
                'target_account' => $friendRequest->account
            ]
        );
    }
}
