<?php

namespace Tests\Feature\Game;

use App\Models\Game\Account;
use App\Models\Game\User;
use Base64Url\Base64Url;
use Exception;
use GDCN\XORCipher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

/**
 * Class RewardTest
 * @package Tests\Feature
 */
class RewardTest extends TestCase
{
    use RefreshDatabase;

    public function test_get(): void
    {
        $user = User::factory()
            ->create();

        $request = $this->post(
            route('game.reward.get'),
            [
                'gameVersion' => 21,
                'binaryVersion' => 35,
                'gdw' => false,
                'udid' => $user->udid,
                'uuid' => $user->id,
                'rewardType' => '0',
                'secret' => 'Wmfd2893gb7',
                'chk' => '5nlqVBAgFDQMB', // 114514
                'r1' => 0,
                'r2' => 0
            ]
        );

        $response = $request->getContent();
        $rewardString = explode('|', $response)[0];

        // Check chk decoded
        $rewardObject = explode(':', XORCipher::cipher(Base64Url::decode(substr($rewardString, 5)), 59182));
        self::assertEqualsIgnoringCase(114514, $rewardObject[2]);

        // Check hash
        $rewardHash = sha1(substr($rewardString, 5) . 'pC26fpYaQCtg');
        $request->assertSee("|$rewardHash");
    }

    public function test_get_with_account(): void
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        try {
            $request = $this->post(
                route('game.reward.get'),
                [
                    'gameVersion' => 21,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $account->id,
                    'gjp' => 'AgUGBgMF',
                    'udid' => $account->user->udid,
                    'uuid' => $account->user->id,
                    'rewardType' => '0',
                    'secret' => 'Wmfd2893gb7',
                    'chk' => '5nlqVBAgFDQMB', // 114514
                    'r1' => 0,
                    'r2' => 0
                ]
            );


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
        $request->assertSee("|$rewardHash");
    }
}
