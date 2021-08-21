<?php

namespace Tests\Feature\Game\Level;

use App\Models\Game\Level\Gauntlet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

/**
 * Class GauntletTest
 * @package Tests\Feature
 */
class GauntletTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        /** @var Gauntlet $gauntlet */
        $gauntlet = Gauntlet::factory()
            ->create([
                'gauntlet_id' => 1
            ]);

        $request = $this->post(
            route('game.level.gauntlet.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'secret' => 'Wmfd2893gb7'
            ]
        );


        $request->assertSee("1:$gauntlet->gauntlet_id:3:$gauntlet->levelIds");
    }
}
