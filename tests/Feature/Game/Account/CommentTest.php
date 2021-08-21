<?php

namespace Tests\Feature\Game\Account;

use App\Models\Game\Account;
use App\Models\Game\Account\Comment;
use Base64Url\Base64Url;
use GDCN\Hash\Components\CommentUploadChk as CommentUploadChkComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function config;
use function route;

/**
 * Class CommentTest
 * @package Tests\Feature
 */
class CommentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_get(): void
    {
        /** @var Comment $comment */
        $comment = Comment::factory()
            ->create();

        $request = $this->post(
            route('game.account.comment.list'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $comment->account,
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("2~$comment->content");
        $request->assertSee("4~$comment->likes");
        $request->assertSee("6~$comment->id");
    }

    public function test_get_with_page(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        $perPage = config('game.perPage', 10);
        Comment::factory()
            ->state(['account' => $account->id])
            ->count(++$perPage)
            ->create();

        $request = $this->post(
            route('game.account.comment.list'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'page' => 1,
                'total' => 0,
                'secret' => 'Wmfd2893gb7'
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
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        $content = Base64Url::encode($this->faker->word, true);

        $request = $this->post(
            route('game.account.comment.upload'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'userName' => $account->name,
                'comment' => $content,
                'secret' => 'Wmfd2893gb7',
                'cType' => 1,
                'chk' => app(CommentUploadChkComponent::class)->encode($account->name, 1, $content)
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_account_comments',
            [
                'account' => $account->id,
                'content' => $content
            ]
        );
    }

    public function test_delete(): void
    {
        /** @var Comment $comment */
        $comment = Comment::factory()
            ->create();

        $request = $this->post(
            route('game.account.comment.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $comment->account,
                'gjp' => 'AgUGBgMF',
                'commentID' => $comment->id,
                'secret' => 'Wmfd2893gb7',
                'cType' => '1'
            ]
        );

        $request->assertOk();
        $this->assertDeleted($comment);
    }
}
