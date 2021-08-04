<?php

namespace Tests\Feature\Game\Account;

use App\Models\Game\Account;
use App\Services\Game\Account\SaveDataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use function route;

/**
 * Class SaveDataTest
 * @package Tests\Feature
 */
class SaveDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_url(): void
    {
        Storage::fake('oss');

        /** @var Account $account */
        $account = Account::factory()->createOne();

        $request = $this->post(
            route('game.account.url.get'),
            [
                'accountID' => $account->id,
                'type' => 1,
                'secret' => 'Wmfd2893gb7'
            ]
        )->dump();

        $request->assertOk();
        self::assertEquals(
            Request::getHost(),
            $request->getContent()
        );
    }

    public function test_save(): void
    {
        Storage::fake('oss');

        /** @var Account $account */
        $account = Account::factory()->createOne();

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
            ])->dump();

        $request->assertOk();
        Storage::disk('oss')->assertExists("gdcn/saveData/$account->name.dat");
    }

    public function test_load(): void
    {
        Storage::fake('oss');
        $service = app(SaveDataService::class);

        /** @var Account $account */
        $account = Account::factory()->createOne();

        $content = Str::random();
        $service->save($account->name, $content);

        $request = $this->post(
            route('game.account.data.load'),
            [
                'userName' => $account->name,
                'password' => 123456,
                'secret' => 'Wmfv3899gc9',
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false
            ]);

        $request->assertOk();
        self::assertEquals("$content;21;35;$content", $request->getContent());
    }
}
