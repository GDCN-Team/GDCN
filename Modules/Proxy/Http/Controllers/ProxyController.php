<?php

namespace Modules\Proxy\Http\Controllers;

use App\Enums\Game\ResponseCode;
use App\Exceptions\Newgrounds\SongDisabledException;
use App\Exceptions\Newgrounds\SongGetException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Modules\Newgrounds\Http\Controllers\NewgroundsController;
use Modules\Proxy\Entities\Traffic;

class ProxyController extends Controller
{
    public string $proxyServer = 'http://127.0.0.1:10809';
    public string $baseURL = 'http://www.boomlings.com';

    public function __construct(
        protected NewgroundsController $newgroundsController
    )
    {
    }

    public function proxy(Request $request): string
    {
        $path = $request->getRequestUri();
        $url = $this->baseURL . $path;
        $queries = $request->all();
        if ($result = $this->processRequest($path, $queries)) {
            return $result;
        }

        return $this->post($url, $queries, true);
    }

    public function processRequest(string $path, array $queries)
    {
        switch ($path) {
            case '/getGJSongInfo.php':
                try {
                    return $this->newgroundsController->get($queries['songID']);
                } catch (SongDisabledException) {
                    return ResponseCode::SONG_DISABLE;
                } catch (SongGetException) {
                    return ResponseCode::SONG_NOT_FOUND;
                }
        }

        return null;
    }

    public function post(string $url, array $queries = [], bool $recordTraffic = false): string
    {
        $req = Http::asForm()
            ->withOptions([
                'proxy' => $this->proxyServer
            ])->post($url, $queries);

        $response = $req->body();
        if ($recordTraffic) {
            $this->addTraffic($url, http_build_query($queries), $response);
        }

        return $response;
    }

    public function addTraffic(string $url, string $query, string $response)
    {
        $today = date('Y-m-d');
        $traffic = Traffic::firstOrNew([
            'date' => $today
        ]);

        $traffic += array_sum([$url, $query, $response]);
        $traffic->save();
    }

    public function get(string $url, array $queries = [], bool $recordTraffic = false): string
    {
        $req = Http::asForm()
            ->withOptions([
                'proxy' => $this->proxyServer
            ])->get($url, $queries);

        $response = $req->body();
        if ($recordTraffic) {
            $this->addTraffic($url, http_build_query($queries), $response);
        }

        return $response;
    }

    public function getTraffics(int $page): string
    {
        return Traffic::query()
            ->forPage($page)
            ->get(['count', 'date'])
            ->toJson();
    }
}
