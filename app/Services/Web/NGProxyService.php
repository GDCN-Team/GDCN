<?php

namespace App\Services\Web;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Presenters\Web\NGProxyPresenter;
use App\Services\Game\NGProxyService as GameNGProxyService;
use Inertia\Inertia;
use Inertia\Response;

class NGProxyService
{
    public function __construct(
        public GameNGProxyService  $service,
        public NGProxyPresenter    $presenter,
        public NotificationService $notification
    )
    {
    }

    public function getSongInfo(int $songID): Response
    {
        try {
            $song = $this->service->getSong($songID);
            Inertia::share('song', $song);
        } catch (SongGetException) {
            $this->notification->sendMessage('error', '歌曲获取失败');
        } catch (SongNotFoundException) {
            $this->notification->sendMessage('error', '歌曲不存在(或未找到)');
        }

        return $this->presenter->renderHomePage();
    }
}
