<?php

namespace Modules\GDProxy\Http\Controllers;

use App\Http\Controllers\Web\Traits\ResponseTrait;
use GDCN\GDObject;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request as RequestFacade;
use Modules\GDProxy\Entities\NGProxyBind;
use Modules\GDProxy\Entities\ReplaceSongLevel;
use Modules\GDProxy\Entities\Traffic;
use Modules\NGProxy\Entities\Application;
use Modules\NGProxy\Entities\ApplicationUser;
use Modules\NGProxy\Exceptions\SongDisabledException;
use Modules\NGProxy\Exceptions\SongGetException;
use Modules\NGProxy\Exceptions\SongSaveException;
use Modules\NGProxy\Http\Controllers\NGProxyController;
use Modules\Proxy\Exceptions\ProxyFailedException;
use Modules\Proxy\Http\Controllers\ProxyController;

/**
 * Class GDProxyController
 * @package Modules\GDProxy\Http\Controllers
 */
class GDProxyController extends Controller
{
    use ResponseTrait;

    /**
     * @var string
     */
    public string $gdServer = 'http://www.boomlings.com/database';

    /**
     * @var string
     */
    public string $app_id = 'HssgUeHhgZ8DsiztqWQOvxHfE3BTqEmAVMv5DR2AJGAzsa59ZqlYOGVWD66AFsHe';

    /**
     * GDProxyController constructor.
     * @param ProxyController $proxy
     * @param NGProxyController $NGProxy
     */
    public function __construct(
        public ProxyController   $proxy,
        public NGProxyController $NGProxy
    )
    {
    }

    /**
     * @param Request $request
     * @return string
     * @throws ProxyFailedException
     * @throws SongDisabledException
     * @throws SongGetException
     * @throws SongSaveException
     */
    public function proxy(Request $request): string
    {
        $uri = $request->getRequestUri();

        $url = $this->gdServer . $uri;
        $queries = $request->all();

        if ($uri === '/getGJSongInfo.php') { // 转到 NGProxy
            $NGProxy = app(NGProxyController::class);
            $NGProxy->setAppId($this->app_id);
            return $NGProxy->getObject($queries['songID']);
        }

        $req = $this->proxy->getInstance()
            ->asForm()
            ->post($url, $queries);

        $response = $req->body();
        if (empty($response) || $response < 0 || !$req->ok()) {
            throw new ProxyFailedException();
        }

        if ($uri === '/getGJUserInfo20.php') {
            $userInfo = GDObject::split($response, ':');
            if ($queries['accountID'] === $userInfo[16]) {
                $this->bindToNGProxy($queries['accountID'], $userInfo[2], $userInfo[1]);
            }
        }

        if ($uri === '/getGJLevels21.php') {
            $levelParts = explode('#', $response);
            $levelObjects = $levelParts[0];
            $levelObjects = explode('|', $levelObjects);

            foreach ($levelObjects as $index => $levelObject) {
                $levelObject = GDObject::split($levelObject, ':');
                $levelID = $levelObject[1];

                if ($replace = ReplaceSongLevel::whereLevel($levelID)->first()) {
                    $levelObject[12] = 0;
                    $levelObject[35] = $replace->song;
                    $levelParts[2] .= '~:~' . $this->NGProxy->getObject($replace->song);
                }

                $levelObjects[$index] = GDObject::merge($levelObject, ':');
            }

            $levelParts[0] = implode('|', $levelObjects);
            $response = implode('#', $levelParts);
        }

        $traffic = Traffic::firstOrNew([
            'date' => date('Y-m-d')
        ]);

        $traffic->count += strlen($url) + strlen(http_build_query($queries)) + strlen($response);
        $traffic->save();

        return $response;
    }

    protected function bindToNGProxy($accountID, $userID, $name)
    {
        $app = Application::whereAppId($this->app_id)->first();
        $query = NGProxyBind::whereAccountId($accountID);
        if (!$query->exists()) {
            $user = new ApplicationUser();
            $user->app_id = $app->id;
            $user->user_id = $userID;
        } else {
            $user = ApplicationUser::where([
                'app_id' => $app->id,
                'user_id' => $query->value('ngproxy_user_id')
            ])->first();

            if (!$user) {
                $user = new ApplicationUser();
                $user->app_id = $app->id;
                $user->user_id = $userID;
            }
        }

        $user->bind_ip = RequestFacade::ip();
        $user->save();

        if (!$query->exists()) {
            $bind = new NGProxyBind();
            $bind->account_id = $accountID;
            $bind->account_name = $name;
            $bind->ngproxy_user_id = $user->id;
            $bind->save();
        }
    }
}
