<?php

namespace Tests\Feature;

use App\Models\GameLevelGauntlet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class GameLevelGauntletTest
 * @package Tests\Feature
 */
class GameLevelGauntletTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        /** @var GameLevelGauntlet $gauntlet */
        $gauntlet = GameLevelGauntlet::factory()->create();

        $request = $this->post(
            route('game.level.gauntlet.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'secret' => 'Wmfd2893gb7'
            ]
        );

        $request->assertOk();
        $request->assertSee("1:{$gauntlet->id}:3:{$gauntlet->levels}");
    }
}
