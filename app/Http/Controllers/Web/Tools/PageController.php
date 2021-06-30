<?php

namespace App\Http\Controllers\Web\Tools;

use App\Http\Controllers\Controller;
use App\Presenter\WebToolsPresenter;
use App\Repositories\Game\CustomSongRepository;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * @var WebToolsPresenter
     */
    protected $presenter;

    /**
     * @var \App\Repositories\Game\CustomSongRepository
     */
    protected $repository;

    /**
     * WebToolsPageController constructor.
     * @param WebToolsPresenter $presenter
     * @param CustomSongRepository $repository
     */
    public function __construct(WebToolsPresenter $presenter, CustomSongRepository $repository)
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
