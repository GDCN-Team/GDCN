<?php

namespace App\Services\Game;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use GDCN\GDObject;
use GDCN\Hash\Enums\Salts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GDProxyService
{
    public function __construct(
        public ProxyService $proxy
    )
    {
    }

    /**
     * @throws SongNotFoundException
     */
    public function proxy(string $path, array $data = []): string
    {
        if ($result = $this->preProcessRequest($path, $data)) {
            Log::debug('[GDProxy] Request preprocessed.', [
                'data' => $result
            ]);

            return $result;
        }

        $response = $this->proxy
            ->getProxyInstance()
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

    /**
     * @throws SongNotFoundException
     */
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
                $parts = explode('#', $response);

                $levelObject = GDObject::split($parts[0], ':');
                $levelObject[27] = 'Aw==';
                $parts[0] = GDObject::merge($levelObject, ':');

                $levelHash = implode(',', [
                    $levelObject[6],
                    $levelObject[18],
                    $levelObject[17],
                    $levelObject[1] ?? $data['levelID'],
                    $levelObject[38] ?? 0,
                    $levelObject[19],
                    1,
                    $levelObject[41] ?? 0
                ]);

                $parts[2] = sha1($levelHash . Salts::LEVEL);

                return implode('#', $parts);
            default:
                return null;
        }
    }
}
