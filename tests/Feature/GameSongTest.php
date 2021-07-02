<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameSongTest extends TestCase
{

    use RefreshDatabase;

    public function test_get(): void
    {
        $request = $this->post(
            route('game.song.get'),
            [
                'songID' => 1,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee('1~|~');
    }

    public function test_get_top_artists(): void
    {
        $this->test_get();

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
