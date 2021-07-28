<?php

namespace Modules\GDProxy\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GDProxy\Entities\Traffic;
use Modules\NGProxy\Exceptions\SongGetException;
use Modules\NGProxy\Http\Controllers\NGProxyController;
use Modules\Proxy\Exceptions\ProxyFailedException;
use Modules\Proxy\Http\Controllers\ProxyController;

/**
 * Class GDProxyController
 * @package Modules\GDProxy\Http\Controllers
 */
class GDProxyController extends Controller
{
    /**
     * @var string
     */
    public string $gdServer = 'http://www.boomlings.com/database';

    /**
     * GDProxyController constructor.
     * @param ProxyController $proxy
     */
    public function __construct(
        public ProxyController $proxy
    )
    {
    }

    /**
     * @param Request $request
     * @return string
     * @throws ProxyFailedException
     * @throws SongGetException
     */
    public function proxy(Request $request): string
    {
        $uri = $request->getRequestUri();

        $url = $this->gdServer . $uri;
        $queries = $request->all();

        if ($uri === '/getGJSongInfo.php') { // 转到 NGProxy
            return app(NGProxyController::class)->getObject($queries['songID']);
        }

        $req = $this->proxy->getInstance()
            ->asForm()
            ->post($url, $queries);

        $response = $req->body();
        if (empty($response) || $response < 0 || !$req->ok()) {
            throw new ProxyFailedException();
        }

        $traffic = Traffic::firstOrNew([
            'date' => date('Y-m-d')
        ]);

        $traffic->count += strlen($url) + strlen(http_build_query($queries)) + strlen($response);
        $traffic->save();

        return $response;
    }

    public function getTraffics()
    {
        return Traffic::paginate(7);
    }
}
