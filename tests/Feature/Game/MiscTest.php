<?php

namespace Tests\Feature\Game;

use App\Enums\Game\ResponseCode;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\User;
use Base64Url\Base64Url;
use GDCN\XORCipher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use function route;

/**
 * Class MiscTest
 * @package Tests\Feature
 */
class MiscTest extends TestCase
{
    use RefreshDatabase;

    public function test_dislike_level(): void
    {
        /** @var Level $level */
        $level = Level::factory()->create();

        try {
            $user = User::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, false);
    }

    /**
     * @param User|null $user
     * @param $item
     * @param $type
     * @param $like
     * @param null $useAcc
     */
    private function test_like(?User $user, $item, $type, $like, $useAcc = null): void
    {
        if (!$user->account) {
            /** @var Account $account */
            $account = Account::factory()->create();
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
            'special' => false,
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
            ]
        );
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
                sha1("0$itemID" . ($like ? 1 : 0) . "$type$rs$accountID$udid{$uuid}ysg6pUrtjn0J"),
                58281), true);
    }

    public function test_dislike_level_with_account(): void
    {
        /** @var Level $level */
        $level = Level::factory()->create();

        try {
            $user = User::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, false, true);
    }

    public function test_like_level(): void
    {
        /** @var Level $level */
        $level = Level::factory()->create();

        try {
            $user = User::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, true);
    }

    public function test_like_level_with_account(): void
    {
        /** @var Level $level */
        $level = Level::factory()->create();

        try {
            $user = User::whereId($level->user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            self::fail($e->getMessage());
        }

        $this->test_like($user, $level, 1, true, true);
    }

    public function test_dislike_level_comment(): void
    {
        /** @var LevelComment $comment */
        $comment = LevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, false);
    }

    public function test_dislike_level_comment_with_account(): void
    {
        /** @var LevelComment $comment */
        $comment = LevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, false, true);
    }

    public function test_like_level_comment(): void
    {
        /** @var LevelComment $comment */
        $comment = LevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, true);
    }

    public function test_like_level_comment_with_account(): void
    {
        /** @var LevelComment $comment */
        $comment = LevelComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 2, true, true);
    }

    public function test_dislike_account_comment(): void
    {
        /** @var AccountComment $comment */
        $comment = AccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, false);
    }

    public function test_dislike_account_comment_with_account(): void
    {
        /** @var AccountComment $comment */
        $comment = AccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, false, true);
    }

    public function test_like_account_comment(): void
    {
        /** @var AccountComment $comment */
        $comment = AccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, true);
    }

    public function test_like_account_comment_with_account(): void
    {
        /** @var AccountComment $comment */
        $comment = AccountComment::factory()->create();

        $this->test_like($comment->sender->user, $comment, 3, true, true);
    }

    public function test_restore_items(): void
    {
        $request = $this->post(
            route('game.item.restore'),
            [
                'udid' => 'S' . mt_rand(),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEqualsIgnoringCase(
            ResponseCode::RESTORE_ITEM_FAILED,
            $request->getContent()
        );
    }
}
