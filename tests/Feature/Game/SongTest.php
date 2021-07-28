<?php

namespace Tests\Feature\Game;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Modules\GDProxy\Http\Controllers\GDProxyController;
use Modules\NGProxy\Entities\Song;
use Tests\TestCase;
use function route;

class SongTest extends TestCase
{

    use RefreshDatabase;

    public function test_get(): void
    {
        Http::fake();

        $request = $this->post(
            route('game.song.get'),
            [
                'songID' => 1,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $GDProxy = app(GDProxyController::class);
        Http::assertSent(function ($request) use ($GDProxy) {
            return in_array(
                $request->url(),
                [
                    $GDProxy->gdServer . '/getGJSongInfo.php',
                    $GDProxy->gdServer . '/getGJLevels21.php'
                ]
            );
        });

        $request->dump();
        $request->assertOk();
    }

    public function test_get_top_artists(): void
    {
        Song::factory()->createOne();

        $request = $this->post(
            route('game.artists.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 0,
                'total' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee('4:');
    }
}
