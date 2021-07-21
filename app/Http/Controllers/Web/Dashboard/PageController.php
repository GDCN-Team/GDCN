<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Presenter\Web\DashboardPresenter;
use Inertia\Response;

/**
 * Class WebDashboardPageController
 * @package App\Http\Controllers
 */
class PageController extends Controller
{
    /**
     * @var DashboardPresenter
     */
    protected $presenter;

    /**
     * WebDashboardPageController constructor.
     * @param DashboardPresenter $presenter
     */
    public function __construct(DashboardPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @return Response
     */
    public function renderHome(): Response
    {
        return $this->presenter->home();
    }

    /**
     * @return Response
     */
    public function renderProfile(): Response
    {
        return $this->presenter->profile();
    }

    /**
     * @return Response
     */
    public function renderSetting(): Response
    {
        return $this->presenter->setting();
    }

    /**
     * @return Response
     */
    public function renderPasswordChange(): Response
    {
        return $this->presenter->passwordChange();
    }
}
