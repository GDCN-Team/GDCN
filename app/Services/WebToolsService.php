<?php

namespace App\Services;

use App\Models\GameAccount;
use App\Models\GameAccountLink;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

/**
 * Class WebToolsService
 * @package App\Services
 */
class WebToolsService
{
    /**
     * @var WebNoticeService
     */
    protected $noticeService;

    /**
     * WebToolsService constructor.
     * @param WebNoticeService $noticeService
     */
    public function __construct(WebNoticeService $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @param $host
     * @param $name
     * @param $password
     * @return Response
     */
    public function linkAccount($host, $name, $password): Response
    {
        /** @var GameAccount $account */
        $account = Auth::user();

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
            return Inertia::location(route('tools.account.link'));
        }

        [$accountID, $userID] = explode(',', $response);
        $exists = GameAccountLink::whereHost($host)
            ->whereTargetAccountId($accountID)
            ->whereTargetUserId($userID)
            ->exists();

        if ($exists) {
            $this->noticeService->sendErrorNotice('该账号已被绑定');
            return Inertia::location(route('tools.account.link'));
        }

        $link = new GameAccountLink;
        $link->host = $host;
        $link->account = $account->id;
        $link->target_account_id = $accountID;
        $link->target_user_id = $userID;
        $link->target_name = $name;
        $link->save();

        $this->noticeService->sendSuccessNotice('链接成功!');
        return Inertia::location(route('tools.account.link'));
    }

    /**
     * @param $id
     * @return Response
     */
    public function unlinkAccount($id): Response
    {
        /** @var GameAccount $account */
        $account = Auth::user();

        $link = GameAccountLink::whereId($id)->first();
        if (!$link) {
            $this->noticeService->sendErrorNotice('链接不存在(或未找到)');
            return Inertia::location(route('tools.account.link'));
        }

        if ($link->account === $account->id) {
            $link->delete();
            $this->noticeService->sendSuccessNotice('解绑成功!');
            return Inertia::location(route('tools.account.link'));
        }

        $this->noticeService->sendErrorNotice('解绑失败', '原因: 该链接不属于你');
        return Inertia::location(route('tools.account.link'));
    }
}
