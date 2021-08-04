<?php

namespace Tests\Feature\Game\Level;

use App\Models\Game\Level\Pack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

/**
 * Class PackTest
 * @package Tests\Feature
 */
class PackTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        /** @var Pack $pack */
        $pack = Pack::factory()->create();

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

        $request->assertOk();
        $request->assertSee("1:$pack->id:2:$pack->name:3:$pack->levels");
    }
}
