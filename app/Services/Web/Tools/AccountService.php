<?php

namespace App\Services\Web\Tools;

use App\Exceptions\Web\Tools\AccountLinkException;
use App\Exceptions\Web\Tools\AccountUnLinkException;
use App\Models\Game\Account\Link;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AccountService
{
    /**
     * @var array
     */
    public array $servers;

    /**
     * @param string $server
     * @param string $name
     * @param string $password
     * @return bool
     * @throws AccountLinkException
     */
    public function link(string $server, string $name, string $password): bool
    {
        if (!$serverProperty = Arr::get($this->servers, $server)) {
            return false;
        }

        $serverUrl = "{$serverProperty['endpoint']}/accounts/loginGJAccount.php";
        $linkServerName = Arr::get($serverProperty, 'name', $server);

        $response = Http::post($serverUrl, [
            'udid' => Str::uuid()->toString(),
            'userName' => $name,
            'password' => $password,
            'secret' => 'Wmfv3899gc9'
        ])->body();

        if ($response <= 0) {
            if ($response === '-1') {
                throw new AccountLinkException("登录失败, 错误码: $response");
            }

            throw new AccountLinkException("Robtop 不喜欢你并返回了错误码: $response");
        }

        [$accountID, $userID] = explode(',', $response);
        if (empty($accountID) || empty($userID)) {
            return false;
        }

        $link = Link::where([
            'target_account_id' => $accountID,
            'target_user_id' => $userID
        ]);

        if (!$link->exists()) {
            $link = new Link();
            $link->server = $linkServerName;
            $link->account = Auth::id();
            $link->target_name = $name;
            $link->target_account_id = $accountID;
            $link->target_user_id = $userID;
            return $link->save();
        }

        throw new AccountLinkException('与该账号的链接已存在');
    }

    /**
     * @param Link $link
     * @return bool
     * @throws AccountUnLinkException
     */
    public function unlink(Link $link): bool
    {
        $account = Auth::user();
        if (!$link->owner->is($account)) {
            throw new AccountUnLinkException('这个链接不属于你');
        }

        return $link->delete();
    }

    /**
     * @param array $servers
     * @return AccountService
     */
    public function setServers(array $servers): AccountService
    {
        $this->servers = $servers;
        return $this;
    }
}
