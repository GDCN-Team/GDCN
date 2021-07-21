<?php

namespace App\Http\Controllers\Web\Tools;

use App\Http\Controllers\Controller;
use App\Presenter\Web\ToolsPresenter;
use App\Repositories\Game\CustomSongRepository;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * @var ToolsPresenter
     */
    protected $presenter;

    /**
     * @var CustomSongRepository
     */
    protected $repository;

    /**
     * WebToolsPageController constructor.
     * @param ToolsPresenter $presenter
     * @param CustomSongRepository $repository
     */
    public function __construct(ToolsPresenter $presenter, CustomSongRepository $repository)
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
