<?php

namespace App\Services\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\SongGetException;
use App\Http\Controllers\Game\NGProxyController;
use GDCN\GDObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Str;

class GDProxyService
{
    public function __construct(
        public ProxyService $proxy
    )
    {
    }

    public function proxy(string $path, array $data = []): string
    {
        if ($result = $this->preProcessRequest($path, $data)) {
            Log::debug('[GDProxy] Request preprocessed.', [
                'data' => $result
            ]);

            return $result;
        }

        $ip = RequestFacade::ip();
        $response = $this->proxy
            ->getProxyInstance()
            ->withHeaders([
                'x-forwarded-for' => $ip,
                'x-real-ip' => $ip
            ])
            ->post(
                $this->proxy->geometry_dash_base_url . Str::start($path, '/'),
                $data
            )->body();

        Log::debug('[GDProxy] Requested to official server.', [
            'data' => $response
        ]);

        if ($result = $this->processResponse($path, $data, $response)) {
            Log::debug('[GDProxy] Processed response.', [
                'data' => $result
            ]);

            return $result;
        }

        return $response;
    }

    protected function preProcessRequest(string $path, array $data = []): ?string
    {
        switch ($path) {
            case '/getGJSongInfo.php':
                try {
                    return app(NGProxyService::class)->getSongObjectForGDProxy($data['songID']);
                } catch (SongGetException) {
                    return ResponseCode::SONG_GET_FAILED;
                }
            default:
                return null;
        }
    }

    protected function processResponse(string $path, array $data = [], string $response = null): ?string
    {
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
