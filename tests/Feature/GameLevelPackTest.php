<?php

namespace Tests\Feature;

use App\Models\GameLevelPack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class GameLevelPackTest
 * @package Tests\Feature
 */
class GameLevelPackTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        /** @var GameLevelPack $pack */
        $pack = GameLevelPack::factory()->create();

        $request = $this->post(
            route('game.level.pack.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'page' => 0,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->dump();
        $request->assertOk();
        $request->assertSee("1:{$pack->id}:2:{$pack->name}:3:{$pack->levels}");
    }
}
