<?php

namespace Tests\Feature\Game;

use App\Models\Game\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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

        Http::assertSent(function ($request) {
            return in_array(
                $request->url(),
                [
                    'https://dl.geometrydashchinese.com/getGJSongInfo.php',
                    'https://dl.geometrydashchinese.com/getGJLevels21.php'
                ]
            );
        });

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

        $request->assertOk();
        $request->assertSee("4:");
    }
}
