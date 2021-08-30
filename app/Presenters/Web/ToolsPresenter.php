<?php

namespace App\Presenters\Web;

use App\Models\Game\Account\Link;
use App\Models\Game\CustomSong;
use App\Services\Web\ToolsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ToolsPresenter
{
    public function __construct(
        public ToolsService $service
    )
    {
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderHomePage(array $props = []): Response
    {
        return Inertia::render('Tools/Home', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderAccountLinkPage(array $props = []): Response
    {
        Inertia::share('servers', $this->service->servers);
        return Inertia::render('Tools/Account/Link', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderAccountLinkListPage(array $props = []): Response
    {
        Inertia::share('servers', $this->service->servers);
        Inertia::share('links', function () {
            $accountID = Auth::id();
            return Link::whereAccount($accountID)
                ->paginate();
        });

        return Inertia::render('Tools/Account/LinkList', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderLevelTransInPage(array $props = []): Response
    {
        Inertia::share('servers', $this->service->servers);
        return Inertia::render('Tools/Level/TransIn', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderLevelTransOutPage(array $props = []): Response
    {
        Inertia::share('servers', $this->service->servers);
        Inertia::share('links', function () {
            $server = Request::get('server', 'official');

            return Link::where([
                'account' => Auth::id(),
                'server' => $this->service->servers[$server]['name'] ?? $server
            ])->get()->toArray();
        });

        return Inertia::render('Tools/Level/TransOut', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderSongUploadLinkPage(array $props = []): Response
    {
        $songID = Inertia::lazy(function () {
            return $this->service->song->getLatestAvailableMinimumID();
        });

        Inertia::share('song_id', $songID);
        return Inertia::render('Tools/Song/UploadLink', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderSongUploadNeteasePage(array $props = []): Response
    {
        $songID = Inertia::lazy(function () {
            return $this->service->song->getLatestAvailableMinimumID();
        });

        Inertia::share('song_id', $songID);
        return Inertia::render('Tools/Song/UploadNetease', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderSongListPage(array $props = []): Response
    {
        $accountID = Auth::id();
        Inertia::share('accountID', $accountID);

        Inertia::share('songs', function () use ($accountID) {
            if (Request::boolean('me')) {
                return CustomSong::with('uploader:id,name')
                    ->where('uploader', $accountID)
                    ->paginate();
            }

            return CustomSong::with('uploader:id,name')->where('name', 'LIKE', '%' . Request::get('search') . '%')->paginate();
        });

        return Inertia::render('Tools/Song/List', $props);
    }

    /**
     * @param CustomSong $song
     * @param array $props
     * @return RedirectResponse|Response
     */
    public function renderSongEditPage(CustomSong $song, array $props = []): Response|RedirectResponse
    {
        $account = Auth::user();
        abort_if(!$song->getRelationValue('uploader')->is($account), 403);

        if (Str::contains($song->type, 'netease')) {
            $this->service->notification->sendMessage('error', '该歌曲不可修改');
            return back();
        }

        Inertia::share('song', $song);
        return Inertia::render('Tools/Song/Edit', $props);
    }
}
