<?php

namespace App\Presenters\Web;

use App\Exceptions\Game\SongGetException;
use App\Exceptions\Game\SongNotFoundException;
use App\Services\Game\NGProxyService;
use App\Services\Web\NotificationService;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class NGProxyPresenter
{
    public function renderHomePage(array $props = []): Response
    {
        $props['song'] = Inertia::lazy(function () {
            if ($songID = Request::get('query')) {
                try {
                    return app(NGProxyService::class)->getSong($songID)->toArray();
                } catch (SongGetException) {
                    app(NotificationService::class)->sendMessage('error', '歌曲获取失败');
                } catch (SongNotFoundException) {
                    app(NotificationService::class)->sendMessage('error', '歌曲不存在(或未找到)');
                }
            }

            return null;
        });

        return Inertia::render('NGProxy/Home', $props);
    }
}
