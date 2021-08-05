<?php

namespace Modules\GDProxy\Http\Controllers;

use App\Http\Controllers\Web\Traits\ResponseTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request as RequestFacade;
use JetBrains\PhpStorm\ArrayShape;
use Modules\GDProxy\Entities\NGProxyBind;
use Modules\GDProxy\Entities\Traffic;
use Modules\NGProxy\Entities\Application;
use Modules\NGProxy\Entities\ApplicationUser;

class GDProxyApiController extends Controller
{
    use ResponseTrait;

    /**
     * @param GDProxyController $GDProxy
     */
    public function __construct(
        public GDProxyController $GDProxy
    )
    {
    }

    /**
     * @return array
     */
    #[ArrayShape(['status' => "bool", 'msg' => "\null|string", 'data' => "null"])] public function getTraffics(): array
    {
        return $this->response(
            true,
            null,
            Traffic::paginate(7)
        );
    }

    /**
     * @return array
     */
    #[ArrayShape(['status' => "bool", 'msg' => "\null|string", 'data' => "null"])] public function getNGProxyBindedAccount(): array
    {
        if ($app = Application::whereAppId($this->GDProxy->app_id)->first()) {
            $user = ApplicationUser::where([
                'app_id' => $app->id,
                'bind_ip' => RequestFacade::ip()
            ])->first();

            if ($user) {
                return $this->response(
                    true,
                    null,
                    NGProxyBind::whereNgproxyUserId($user->id)->first()
                );
            }
        }

        return $this->response(false, '未找到绑定设备, 请在游戏内打开个人主页以绑定设备');
    }
}
