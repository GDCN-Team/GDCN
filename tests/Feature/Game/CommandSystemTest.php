<?php

namespace Tests\Feature\Game;

use App\Models\Game\Account;
use App\Models\Game\Level;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use function config;
use function route;

class CommandSystemTest extends TestCase
{
    use RefreshDatabase;

    public function _test_account_comment(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        config([
            'game.feature.command.account_comment.enabled' => true,
            'game.feature.command.account_comment.prefix' => '!'
        ]);

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
                'chk' => Str::random()
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee('worked!');
    }

    public function _test_level_comment(): void
    {
        /** @var Account $account */
        $account = Account::factory()
            ->create();

        /** @var Level $level */
        $level = Level::factory()
            ->create();

        config([
            'game.feature.command.level_comment.enabled' => true,
            'game.feature.command.level_comment.prefix' => '!'
        ]);

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
                'chk' => Str::random()
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee('worked!');
    }
}
