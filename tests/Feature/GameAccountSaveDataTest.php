<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class GameAccountSaveDataTest
 * @package Tests\Feature
 */
class GameAccountSaveDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_url(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->createOne();

        $request = $this->post(
            route('game.account.url.get'),
            [
                'accountID' => $account->id,
                'type' => 1,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        self::assertEquals(
            request()->getHost(),
            $request->getContent()
        );
    }

    public function test_save(): void
    {
        $this->fake(function ($storage) {
            Storage::fake($storage['disk']);
        });

        /** @var GameAccount $account */
        $account = GameAccount::factory()->createOne();

        $request = $this->post(
            route('game.account.data.save'),
            [
                'userName' => $account->name,
                'password' => 123456,
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'saveData' => Str::random(),
                'secret' => 'Wmfv3899gc9'
            ]
        );

        $request->assertOk();
        $this->fake(function ($storage) use ($account) {
            Storage::disk($storage['disk'])->assertExists($storage['path'] . '/' . sha1($account->id) . '.dat');
        });
    }

    public function fake(callable $callback): void
    {
        $storages = config('game.storage.saveData');
        foreach ($storages as $storage) {
            if (!empty($storage['disk']) && !empty($storage['path'])) {
                $callback($storage);
            }
        }
    }

    public function test_load(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->createOne();

        $content = Str::random();
        $this->fake(function ($storage) use ($content, $account) {
            Storage::fake($storage['disk'])->put($storage['path'] . '/' . sha1($account->id) . '.dat', $content);
        });

        $request = $this->post(
            route('game.account.data.load'),
            [
                'userName' => $account->name,
                'password' => 123456,
                'secret' => 'Wmfv3899gc9',
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false
            ]
        );

        $request->assertOk();
        self::assertEquals("{$content};21;35;{$content}", $request->getContent());
    }
}
