<?php

namespace App\Services\Web\Tools;

use App\Exceptions\Web\Tools\UnknownServerException;
use App\Game\Helpers;
use App\Models\GameAccount;
use App\Models\GameAccountLink;
use App\Presenter\WebToolsPresenter;
use App\Services\Web\NoticeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Response as InertiaResponse;
use function app;

/**
 * Class AccountService
 * @package App\Services
 */
class AccountService
{
    /**
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var WebToolsPresenter
     */
    protected $presenter;

    /**
     * AccountService constructor.
     * @param WebToolsPresenter $presenter
     * @param NoticeService $noticeService
     */
    public function __construct(WebToolsPresenter $presenter, NoticeService $noticeService)
    {
        $this->presenter = $presenter;
        $this->noticeService = $noticeService;
    }

    /**
     * @param $serverAlias
     * @param $name
     * @param $password
     * @return InertiaResponse
     */
    public function linkAccount($serverAlias, $name, $password): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        try {
            $host = app(Helpers::class)->getServerHostFromAlias($serverAlias);
        } catch (UnknownServerException $e) {
            $this->noticeService->sendErrorNotice('未知服务器');
            return $this->presenter->accountLink();
        }

        $request = Http::asForm()
            ->post("http://{$host}/accounts/loginGJAccount.php", [
                'userName' => $name,
                'password' => $password,
                'udid' => 'S' . mt_rand(),
                'sID' => 0,
                'secret' => 'Wmfv3899gc9'
            ]);

        $response = $request->body();
        if ($response === '-1') {
            $this->noticeService->sendErrorNotice('登录失败', '错误码: ' . $response);
            return $this->presenter->accountLink();
        }

        [$accountID, $userID] = explode(',', $response);
        $exists = GameAccountLink::whereHost($host)
            ->whereTargetAccountId($accountID)
            ->whereTargetUserId($userID)
            ->exists();

        if ($exists) {
            $this->noticeService->sendErrorNotice('该账号已被绑定');
            return $this->presenter->accountLink();
        }

        $link = new GameAccountLink();
        $link->host = $host;
        $link->account = $account->id;
        $link->target_account_id = $accountID;
        $link->target_user_id = $userID;
        $link->target_name = $name;
        $link->save();

        $this->noticeService->sendSuccessNotice('链接成功!');
        return $this->presenter->accountLink();
    }

    /**
     * @param $id
     * @return InertiaResponse
     */
    public function unlinkAccount($id): InertiaResponse
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        $link = GameAccountLink::whereId($id)->first();
        if (!$link) {
            $this->noticeService->sendErrorNotice('链接不存在(或未找到)');
            return $this->presenter->accountLink();
        }

        if ($link->account === $account->id) {
            $link->delete();
            $this->noticeService->sendSuccessNotice('解绑成功!');
            return $this->presenter->accountLink();
        }

        $this->noticeService->sendErrorNotice('解绑失败', '原因: 该链接不属于你');
        return $this->presenter->accountLink();
    }
}
