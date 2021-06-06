<?php

namespace App\Http\Controllers;

use App\Models\GameAccountLink;
use App\Models\GameCustomSong;
use App\Presenter\WebToolsPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Response;

class WebToolsPageController extends Controller
{
    /**
     * @var WebToolsPresenter
     */
    protected $presenter;

    /**
     * WebToolsPageController constructor.
     * @param WebToolsPresenter $presenter
     */
    public function __construct(WebToolsPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @return Response
     */
    public function renderAccountLinkPage(): Response
    {
        return $this->presenter->accountLink(
            GameAccountLink::whereAccount(Auth::id())
                ->get(['id', 'host', 'target_name'])
                ->toArray()
        );
    }

    /**
     * @return Response
     */
    public function renderSongListPage(): Response
    {
        return $this->presenter->songList(
            GameCustomSong::query()
                ->with('owner:id,name')
                ->get(['id', 'name', 'author_name', 'download_url', 'song_id', 'size', 'uploader'])
        );
    }

    /**
     * @return Response
     */
    public function renderUploadLinkPage(): Response
    {
        return $this->presenter->uploadLink();
    }

    /**
     * @return Response
     */
    public function renderUploadNeteasePage(): Response
    {
        return $this->presenter->uploadNetease();
    }

    public function renderEditSongPage(Request $request)
    {
        $data = $request->validate([
            'id' => [
                'required',
                Rule::exists(GameCustomSong::class)
            ]
        ]);

        return $this->presenter->editSong($data['id']);
    }
}
