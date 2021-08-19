<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\SongGetException;
use App\Http\Controllers\Controller;
use GDCN\GDObject;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GDProxyController extends Controller
{
    public string $base_url = 'http://www.boomlings.com/database';
    public string $proxy_url = 'http://127.0.0.1:10809';

    public function proxy(Request $request): string
    {
        if ($result = $this->preProcessRequest($request)) {
            Log::debug('[GDProxy] Request preprocessed.', [
                'data' => $result
            ]);

            return $result;
        }

        $response = $this->getProxyInstance()
            ->post(
                $this->base_url . $request->getRequestUri(),
                $request->all()
            )->body();

        Log::debug('[GDProxy] Requested to official server.', [
            'data' => $result
        ]);

        if ($result = $this->processResponse($request, $response)) {
            Log::debug('[GDProxy] Processed response.', [
                'data' => $result
            ]);

            return $result;
        }

        return $response;
    }

    public function getProxyInstance(): PendingRequest
    {
        return Http::asForm()
            ->withOptions(['proxy' => $this->proxy_url]);
    }

    protected function preProcessRequest(Request $request): ?string
    {
        $path = $request->getRequestUri();
        switch ($path) {
            case '/getGJSongInfo.php':
                try {
                    $songID = $request->get('songID');
                    return app(NGProxyController::class)->getObject($songID, false);
                } catch (SongGetException) {
                    return ResponseCode::SONG_GET_FAILED;
                }
            default:
                return null;
        }
    }

    protected function processResponse(Request $request, string $response): ?string
    {
        $path = $request->getRequestUri();
        switch ($path) {
            case '/downloadGJLevel22.php':
                [$levelObject, $levelStringHash, , $moreHash] = explode('#', $response);

                $levelObject = GDObject::split($levelObject, ':');
                $levelObject[27] = 'Aw==';

                $levelHash = implode(',', [
                    $levelObject[6],
                    $levelObject[18],
                    $levelObject[17],
                    $levelObject[1],
                    $levelObject[38],
                    $levelObject[19],
                    '1',
                    $levelObject[41] ?? 0
                ]);

                return implode('#', [$levelObject, $levelStringHash, $levelHash, $moreHash]);
            default:
                return null;
        }
    }
}
