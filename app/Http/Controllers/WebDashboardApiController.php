<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebDashboardPasswordChangeApiRequest;
use App\Http\Requests\WebDashboardUpdateAccountSettingApiRequest;
use App\Services\WebDashboardService;
use Illuminate\Http\Response;

/**
 * Class WebDashboardApiController
 * @package App\Http\Controllers
 */
class WebDashboardApiController extends Controller
{
    /**
     * @var WebDashboardService
     */
    protected $service;

    /**
     * WebDashboardApiController constructor.
     * @param WebDashboardService $service
     */
    public function __construct(WebDashboardService $service)
    {
        $this->service = $service;
    }

    /**
     * @param WebDashboardUpdateAccountSettingApiRequest $request
     * @return Response
     */
    public function updateAccountSetting(WebDashboardUpdateAccountSettingApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->updateAccountSetting($data['name'], $data['email']);
    }

    /**
     * @param WebDashboardPasswordChangeApiRequest $request
     * @return Response
     */
    public function changePassword(WebDashboardPasswordChangeApiRequest $request): Response
    {
        $data = $request->validated();
        return $this->service->changePassword($data['new_password']);
    }
}
