<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\GameAccountLink;
use App\Models\GameCustomSong;
use App\Presenter\WebToolsPresenter;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return Response
     */
    public function renderAccountLinkPage(Request $request): Response
    {
        /** @var GameAccount $account */
        $account = $request->user();

        $links = GameAccountLink::whereAccount($account->id)->get(['id', 'host', 'target_name'])->toArray();
        return $this->presenter->accountLink($links);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function renderSongListPage(Request $request): Response
    {
        /** @var GameAccount $account */
        $account = $request->user();

        $songs = GameCustomSong::query()
            ->with('owner:id,name')
            ->get(['id', 'name', 'author_name', 'download_url', 'song_id', 'size', 'uploader']);


        return $this->presenter->songList($songs);
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
