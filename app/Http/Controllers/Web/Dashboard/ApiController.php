<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\Setting\PasswordChangeApiRequest;
use App\Http\Requests\Web\Dashboard\Setting\UpdateAccountSettingApiRequest;
use App\Services\Web\DashboardService;
use Illuminate\Http\Response;

/**
 * Class WebDashboardApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @var DashboardService
     */
    protected $service;

    /**
     * WebDashboardApiController constructor.
     * @param DashboardService $service
     */
    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    /**
     * @param UpdateAccountSettingApiRequest $request
     * @return Response
     */
    public function updateAccountSetting(UpdateAccountSettingApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->updateAccountSetting($data['name'], $data['email']);
    }

    /**
     * @param PasswordChangeApiRequest $request
     * @return Response
     */
    public function changePassword(PasswordChangeApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->changePassword($data['new_password']);
    }
}
