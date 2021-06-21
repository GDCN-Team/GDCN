<?php

namespace Tests\Feature;

use App\Http\Controllers\Game\HashesController;
use App\Models\GameAccount;
use App\Models\GameLevel;
use Base64Url\Base64Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class GameLevelTest
 * @package Tests\Feature
 */
class GameLevelTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_upload(): void
    {
        $this->fake(function ($storage) {
            Storage::fake($storage['disk']);
        });

        $levelString = Str::random();
        $levelName = $this->faker->word;
        $levelDesc = Base64Url::encode($this->faker->word, true);

        $hash = app(HashesController::class);
        $request = $this->post(
            route('game.level.upload'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'userName' => $this->faker->firstName,
                'levelID' => 0,
                'levelName' => $levelName,
                'levelDesc' => $levelDesc,
                'levelVersion' => 1,
                'levelLength' => 0,
                'audioTrack' => 0,
                'auto' => false,
                'password' => 0,
                'original' => 0,
                'twoPlayer' => false,
                'songID' => 0,
                'objects' => 1,
                'coins' => 0,
                'requestedStars' => 1,
                'unlisted' => false,
                'wt' => 0,
                'wt2' => 0,
                'ldm' => false,
                'extraString' => '0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0',
                'seed' => Str::random(),
                'seed2' => $hash->generateUploadLevelSeed($levelString),
                'levelString' => $levelString,
                'levelInfo' => Str::random(),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $response = $request->getContent();
        $this->fake(function ($storage) use ($response) {
            Storage::disk($storage['disk'])->assertExists($storage['path'] . '/' . sha1($response) . '.dat');
        });

        $request->assertOk();
        $this->assertDatabaseHas('game_levels', [
            'id' => $response,
            'name' => $levelName,
            'desc' => $levelDesc
        ]);
    }

    public function fake(callable $callback): void
    {
        $storages = config('game.storage.levels');
        foreach ($storages as $storage) {
            if (!empty($storage['disk']) && !empty($storage['path'])) {
                $callback($storage);
            }
        }
    }

    public function test_upload_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        $this->fake(function ($storage) {
            Storage::fake($storage['disk']);
        });

        $levelString = Str::random();
        $levelName = $this->faker->word;
        $levelDesc = Base64Url::encode($this->faker->word, true);

        $hash = app(HashesController::class);
        $request = $this->post(
            route('game.level.upload'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'userName' => $this->faker->firstName,
                'levelID' => 0,
                'levelName' => $levelName,
                'levelDesc' => $levelDesc,
                'levelVersion' => 1,
                'levelLength' => 0,
                'audioTrack' => 0,
                'auto' => false,
                'password' => 0,
                'original' => 0,
                'twoPlayer' => false,
                'songID' => 0,
                'objects' => 1,
                'coins' => 0,
                'requestedStars' => 1,
                'unlisted' => false,
                'wt' => 0,
                'wt2' => 0,
                'ldm' => false,
                'extraString' => '0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0_0',
                'seed' => Str::random(),
                'seed2' => $hash->generateUploadLevelSeed($levelString),
                'levelString' => $levelString,
                'levelInfo' => Str::random(),
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $response = $request->getContent();
        $this->fake(function ($storage) use ($response) {
            Storage::disk($storage['disk'])->assertExists($storage['path'] . '/' . sha1($response) . '.dat');
        });

        $this->assertDatabaseHas(
            'game_levels',
            [
                'id' => $response,
                'name' => $levelName,
                'desc' => $levelDesc
            ]
        );
    }

    public function test_get(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        $request = $this->post(
            route('game.level.search'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'type' => 1,
                'str' => '-',
                'diff' => '-',
                'len' => '-',
                'page' => 0,
                'total' => 0,
                'uncompleted' => false,
                'onlyCompleted' => false,
                'featured' => false,
                'original' => false,
                'twoPlayer' => false,
                'coins' => false,
                'epic' => false,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$level->id}:2:{$level->name}:3:{$level->desc}");
    }

    public function test_download(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        $levelString = Str::random();
        $this->fake(function ($storage) use ($levelString, $level) {
            $disk = Storage::fake($storage['disk']);
            $disk->put($storage['path'] . '/' . sha1($level->id) . '.dat', $levelString);
        });

        $request = $this->post(
            route('game.level.download'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'levelID' => $level->id,
                'inc' => 0,
                'extras' => 0,
                'secret' => 'Wmfd2893gb7',
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$level->id}:2:{$level->name}:3:{$level->desc}:4:{$levelString}");
    }

    public function test_download_use_chk(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        $levelString = Str::random();
        $this->fake(function ($storage) use ($levelString, $level) {
            $disk = Storage::fake($storage['disk']);
            $disk->put($storage['path'] . '/' . sha1($level->id) . '.dat', $levelString);
        });

        $hash = app(HashesController::class);
        $rs = Str::random();

        $request = $this->post(
            route('game.level.download'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'udid' => $account->user->udid,
                'uuid' => $account->user->id,
                'levelID' => $level->id,
                'inc' => 0,
                'extras' => 0,
                'secret' => 'Wmfd2893gb7',
                'rs' => $rs,
                'chk' => $hash->encodeChk($hash->generateDownloadLevelChk($level->id, 0, $rs, $account->id, $account->user->udid, $account->user->uuid), $hash->keys['level_seed'])
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$level->id}:2:{$level->name}:3:{$level->desc}");
    }

    public function test_delete(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        $levelString = Str::random();
        $this->fake(function ($storage) use ($levelString, $level) {
            $disk = Storage::fake($storage['disk']);
            $disk->put($storage['path'] . '/' . sha1($level->id) . '.dat', $levelString);
        });

        $request = $this->post(
            route('game.level.delete'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'uuid' => $level->user,
                'udid' => $level->creator->udid,
                'levelID' => $level->id,
                'secret' => 'Wmfv2898gc9'
            ]
        );

        $request->assertOk();
        $this->fake(function ($storage) use ($level) {
            Storage::disk($storage['disk'])->assertMissing($storage['path'] . '/' . sha1($level->id) . '.dat');
        });

        $this->assertDeleted(
            $level->getTable(),
            $level->attributesToArray()
        );
    }

    public function test_report(): void
    {
        /** @var GameLevel $level */
        $level = GameLevel::factory()->create();

        $request = $this->post(
            route('game.level.report'),
            [
                'levelID' => $level->id,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $this->assertDatabaseHas(
            'game_level_reports',
            [
                'level' => $level->id,
                'times' => 1
            ]
        );
    }
}
