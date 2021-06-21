<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use Base64Url\Base64Url;
use GDCN\XORCipher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class GameChallengeTest
 * @package Tests\Feature
 */
class GameChallengeTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        $request = $this->post(
            route('game.challenge.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'world' => false,
                'secret' => 'Wmfd2893gb7',
                'chk' => 'Test_AAgMAQYF' // 114514
            ]
        );

        $request->assertOk();
        $response = $request->getContent();
        $challengeString = explode('|', $response)[0];

        // Check chk decoded
        $challengeObject = explode(':', XORCipher::cipher(Base64Url::decode(substr($challengeString, 5)), 19847));
        self::assertEqualsIgnoringCase(114514, $challengeObject[2]);

        // Check hash
        $challengeHash = sha1(substr($challengeString, 5) . 'oC36fpYaPtdg');
        $request->assertSee("|{$challengeHash}");
    }

    public function test_get_use_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        $request = $this->post(
            route('game.challenge.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'accountID' => $account->id,
                'gjp' => 'AgUGBgMF',
                'udid' => 'S' . mt_rand(),
                'uuid' => 0,
                'world' => false,
                'secret' => 'Wmfd2893gb7',
                'chk' => 'Test_AAgMAQYF' // 114514
            ]
        );

        $request->assertOk();
        $response = $request->getContent();
        $challengeString = explode('|', $response)[0];

        // Check chk decoded
        $challengeObject = explode(':', XORCipher::cipher(Base64Url::decode(substr($challengeString, 5)), 19847));
        self::assertEqualsIgnoringCase(114514, $challengeObject[2]);

        // Check hash
        $challengeHash = sha1(substr($challengeString, 5) . 'oC36fpYaPtdg');
        $request->assertSee("|{$challengeHash}");
    }
}
