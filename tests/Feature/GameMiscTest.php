<?php

namespace Tests\Feature;

use App\Enums\Game\ResponseCode;
use App\Models\GameAccount;
use App\Models\GameAccountComment;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use App\Models\GameUser;
use Base64Url\Base64Url;
use GDCN\XORCipher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class GameMiscTest
 * @package Tests\Feature
 */
class GameMiscTest extends TestCase
{
    use RefreshDatabase;

    public function test_dislike_level(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        try {
            $user = GameUser::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, false);
    }

    /**
     * @param GameUser|null $user
     * @param $item
     * @param $type
     * @param $like
     * @param null $useAcc
     */
    private function test_like(?GameUser $user, $item, $type, $like, $useAcc = null): void
    {
        if (!$user->account) {
            /** @var GameAccount $account */
            $account = GameAccount::factory()->create();
            $user = $account->user;
        }

        $data = [
            'gameVersion' => 21,
            'binaryVersion' => 35,
            'gdw' => false,
            'udid' => $user->udid,
            'uuid' => 0,
            'itemID' => $item->id,
            'like' => (int)$like,
            'type' => $type,
            'secret' => 'Wmfd2893gb7',
            'special' => (int)false,
            'rs' => $rs = Str::random(),
            'chk' => $this->generate_like_chk($item->id, $like, $type, $rs, $useAcc ? $user->account->id : 0, $user->udid, 0)
        ];

        if ($useAcc) {
            $data['accountID'] = $user->account->id;
            $data['gjp'] = 'AgUGBgMF';
        }

        $request = $this->post(
            route('game.item.like'),
            $data);

        $request->assertOk();
        $this->assertDatabaseHas(
            $item->getTable(),
            [
                'id' => $item->id,
                'likes' => ($item->likes ?? 0) + ($like ? 1 : -1)
            ])->dump();
    }

    /**
     * @param $itemID
     * @param $like
     * @param $type
     * @param $rs
     * @param $accountID
     * @param $udid
     * @param $uuid
     * @return string
     */
    private function generate_like_chk($itemID, $like, $type, $rs, $accountID, $udid, $uuid): string
    {
        return Base64Url::encode(
            XORCipher::cipher(
                sha1("0{$itemID}" . ($like ? 1 : 0) . "{$type}{$rs}{$accountID}{$udid}{$uuid}ysg6pUrtjn0J"),
                58281), true);
    }

    public function test_dislike_level_with_account(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        try {
            $user = GameUser::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, false, true);
    }

    public function test_like_level(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        try {
            $user = GameUser::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, true);
    }

    public function test_like_level_with_account(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        try {
            $user = GameUser::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, true, true);
    }

    public function test_dislike_level_comment(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, false);
    }

    public function test_dislike_level_comment_with_account(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, false, true);
    }

    public function test_like_level_comment(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, true);
    }

    public function test_like_level_comment_with_account(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, true, true);
    }

    public function test_dislike_account_comment(): void
    {
        /** @var GameAccountComment $comment */
        $comment = GameAccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, false);
    }

    public function test_dislike_account_comment_with_account(): void
    {
        /** @var GameAccountComment $comment */
        $comment = GameAccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, false, true);
    }

    public function test_like_account_comment(): void
    {
        /** @var GameAccountComment $comment */
        $comment = GameAccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, true);
    }

    public function test_like_account_comment_with_account(): void
    {
        /** @var GameAccountComment $comment */
        $comment = GameAccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, true, true);
    }

    public function test_restore_items(): void
    {
        $request = $this->post(
            route('game.item.restore'),
            [
                'udid' => 'S' . mt_rand(),
                'secret' => 'Wmfd2893gb7'
            ])->dump();

        $request->assertOk();
        self::assertEqualsIgnoringCase(
            ResponseCode::RESTORE_ITEM_FAILED,
            $request->getContent()
        );
    }
}
