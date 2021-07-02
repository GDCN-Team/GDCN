<?php

namespace Tests\Feature;

use App\Models\GameAccount;
use Base64Url\Base64Url;
use Exception;
use GDCN\XORCipher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class GameRewardTest
 * @package Tests\Feature
 */
class GameRewardTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        try {
            $request = $this->post(
                route('game.reward.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'udid' => 'S' . mt_rand(),
                    'uuid' => 0,
                    'rewardType' => 0,
                    'secret' => 'Wmfd2893gb7',
                    'chk' => '5nlqVBAgFDQMB', // 114514
                    'r1' => 0,
                    'r2' => 0
                ]
            );
        } catch (Exception $e) {
            self::fail(
                $e->getMessage()
            );
        }

        $response = $request->getContent();
        $rewardString = explode('|', $response)[0];

        // Check chk decoded
        $rewardObject = explode(':', XORCipher::cipher(Base64Url::decode(substr($rewardString, 5)), 59182));
        self::assertEqualsIgnoringCase(114514, $rewardObject[2]);

        // Check hash
        $rewardHash = sha1(substr($rewardString, 5) . 'pC26fpYaQCtg');
        $request->assertSee("|{$rewardHash}");
    }

    public function test_get_with_account(): void
    {
        /** @var GameAccount $account */
        $account = GameAccount::factory()->create();

        try {
            $request = $this->post(
                route('game.reward.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $account->id,
                    'gjp' => 'AgUGBgMF',
                    'udid' => 'S' . mt_rand(),
                    'uuid' => 0,
                    'rewardType' => 0,
                    'secret' => 'Wmfd2893gb7',
                    'chk' => '5nlqVBAgFDQMB', // 114514
                    'r1' => 0,
                    'r2' => 0
                ]
            );

            $request->dump();
            $request->assertOk();
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }

        $response = $request->getContent();
        $rewardString = explode('|', $response)[0];

        // Check chk decoded
        $rewardObject = explode(':', XORCipher::cipher(Base64Url::decode(substr($rewardString, 5)), 59182));
        self::assertEqualsIgnoringCase(114514, $rewardObject[2]);

        // Check hash
        $rewardHash = sha1(substr($rewardString, 5) . 'pC26fpYaQCtg');
        $request->assertSee("|{$rewardHash}");
    }
}
