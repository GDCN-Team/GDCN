<?php

namespace App\Http\Controllers;

use App\Presenter\WebToolsPresenter;
use App\Repositories\GameCustomSongRepository;
use Inertia\Response;

class WebToolsPageController extends Controller
{
    /**
     * @var WebToolsPresenter
     */
    protected $presenter;

    /**
     * @var GameCustomSongRepository
     */
    protected $repository;

    /**
     * WebToolsPageController constructor.
     * @param WebToolsPresenter $presenter
     * @param GameCustomSongRepository $repository
     */
    public function __construct(WebToolsPresenter $presenter, GameCustomSongRepository $repository)
    {
        $this->presenter = $presenter;
        $this->repository = $repository;
    }

    /**
     * @return Response
     */
    public function renderAccountLinkPage(): Response
    {
        return $this->presenter->accountLink();
    }

    /**
     * @return Response
     */
    public function renderSongListPage(): Response
    {
        return $this->presenter->songList();
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
}
