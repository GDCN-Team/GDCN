<?php

namespace Tests\Feature;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Game\HashesController;
use App\Models\GameAccount;
use App\Models\GameAccountSetting;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class GameLevelCommentTest
 * @package Tests\Feature
 */
class GameLevelCommentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_get(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $request = $this->post(
            route('game.level.comment.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'mode' => 0,
                'levelID' => $comment->level
            ]
        );

        $request->assertOk();
        $request->assertSee("2~{$comment->content}~3~{$comment->sender->user->id}~4~{$comment->likes}");
    }

    public function test_get_with_page(): void
    {
        $perPage = config('game.perPage', 10);

        /** @var GameLevel $level */
        $level = GameLevel::factory()
            ->hasComments(++$perPage)
            ->create();

        $request = $this->post(
            route('game.level.comment.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 1,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'mode' => 0,
                'levelID' => $level->id
            ]
        );
        $request->assertOk();

        $response = $request->getContent();
        $left = explode('#', $response)[0];
        $comments = explode('|', $left);

        self::assertCount(1, $comments);
    }

    public function test_upload(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameLevel $level */
        $level = GameLevel::factory()
            ->create();

        $hash = app(HashesController::class);
        $content = Base64Url::encode($this->faker->word, true);

        $request = $this->post(
            route('game.level.comment.upload'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'userName' => $account->name,
                'comment' => $content,
                'secret' => 'Wmfd2893gb7',
                'levelID' => $level->id,
                'percent' => 0,
                'chk' => $hash->generateUploadLevelCommentChk($account->name, $content, $level->id, 0, true)
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_comments',
            [
                'level' => $level->id,
                'content' => $content
            ]
        );
    }

    public function test_delete(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $request = $this->post(
            route('game.level.comment.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $comment->account,
                'gjp' => 'AgUGBgMF',
                'commentID' => $comment->id,
                'secret' => 'Wmfd2893gb7',
                'levelID' => $comment->level
            ]
        );

        $request->assertOk();
        $this->assertDeleted(
            $comment->getTable(),
            $comment->attributesToArray()
        );
    }

    public function test_get_history_recent(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $request = $this->post(
            route('game.level.comment.history'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'mode' => 0,
                'userID' => $comment->sender->user->id,
                'count' => 25
            ]
        );

        $request->assertSee("1~{$comment->level}~2~{$comment->content}");
    }

    public function test_get_history_most_liked(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        $request = $this->post(
            route('game.level.comment.history'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'mode' => 1,
                'userID' => $comment->sender->user->id,
                'count' => 25
            ]
        );

        $request->assertSee("1~{$comment->level}~2~{$comment->content}");
    }

    public function test_get_history_use_none_policy(): void
    {
        /** @var GameLevelComment $comment */
        $comment = GameLevelComment::factory()->create();

        /** @var GameAccountSetting $setting */
        $setting = $comment->sender->setting()->firstOrNew();
        $setting->message_state = 0;
        $setting->friend_request_state = 0;
        $setting->comment_history_state = 2;
        $setting->save();

        $request = $this->post(
            route('game.level.comment.history'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7',
                'mode' => 1,
                'userID' => $comment->sender->user->id,
                'count' => 25
            ]
        );

        self::assertEqualsIgnoringCase(
            ResponseCode::PERMISSION_DENIED,
            $request->getContent()
        );
    }
}
