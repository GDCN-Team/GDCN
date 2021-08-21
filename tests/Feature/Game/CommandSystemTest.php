<?php

namespace Tests\Feature\Game;

use App\Models\Game\Account;
use App\Models\Game\Level;
use Base64Url\Base64Url;
use GDCN\Hash\Components\CommentUploadChk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

class CommandSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_comment(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        $content = Base64Url::encode('!test', true);
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
                'chk' => app(CommentUploadChk::class)->encode(
                    $account->name,
                    1,
                    $content
                )
            ]
        );

        $request->assertOk();
        $request->assertSee('worked!');
    }

    public function test_account_comment_not_found()
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        $content = Base64Url::encode('!invalid_command', true);
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
                'chk' => app(CommentUploadChk::class)->encode(
                    $account->name,
                    1,
                    $content
                )
            ]
        );

        $request->assertOk();
        $request->assertSee('Command Not Found!');
    }

    public function test_level_comment(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
            ->create();

        $content = Base64Url::encode('!test', true);
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
                'cType' => 0,
                'chk' => app(CommentUploadChk::class)->encode(
                    $account->name,
                    0,
                    $content,
                    $level->id
                )
            ]
        );

        $request->assertOk();
        $request->assertSee('worked!');
    }
}
