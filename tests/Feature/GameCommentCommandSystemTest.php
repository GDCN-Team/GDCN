<?php

namespace Tests\Feature;

use App\Http\Controllers\Web\Tools\Web\Tools\Web\Dashboard\Web\Dashboard\Web\Auth\Game\HashesController;
use App\Models\GameAccount;
use App\Models\GameLevel;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCommentCommandSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_comment(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()
            ->create();

        config([
            'game.feature.command.account_comment.enabled' => true,
            'game.feature.command.account_comment.prefix' => '!'
        ]);

        $hash = app(HashesController::class);
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
                'chk' => $hash->generateUploadAccountCommentChk($account->name, $content, true)
            ]
        );

        $request->assertOk();
        $request->assertSee('worked!');
    }

    public function test_level_comment(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()
            ->create();

        /** @var GameLevel $level */
        $level = GameLevel::factory()
            ->create();

        config([
            'game.feature.command.level_comment.enabled' => true,
            'game.feature.command.level_comment.prefix' => '!'
        ]);

        $hash = app(HashesController::class);
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
                'chk' => $hash->generateUploadLevelCommentChk($account->name, $content, $level->id, 0, true)
            ]
        );

        $request->assertOk();
        $request->assertSee('worked!');
    }
}
