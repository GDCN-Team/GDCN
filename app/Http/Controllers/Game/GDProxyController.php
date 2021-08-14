<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GDProxyController extends Controller
{
    public string $base_url = 'http://www.boomlings.com/database';
    public string $proxy_url = 'http://127.0.0.1:10809';

    /**
     * @param Request $request
     * @return string
     */
    public function proxy(Request $request): string
    {
        if ($result = $this->preProcessRequest($request)) {
            return $result;
        }

        $response = Http::asForm()->withOptions(['proxy' => $this->proxy_url])->post($this->base_url . $request->getRequestUri(), $request->all())->body();

        if ($result = $this->processResponse($request, $response)) {
            return $result;
        }

        return $response;
    }

    protected function preProcessRequest(Request $request)
    {
        switch ($request->getRequestUri()) {
            case '/getGJSongInfo.php':
                // TODO: NGProxy
            default:
                return null;
        }
    }

    protected function processResponse(Request $request, string $response)
    {
        switch ($request->getRequestUri()) {
            case '/downloadGJLevel22.php':
                // TODO: Free Copy
            default:
                return null;
        }
    }
}
