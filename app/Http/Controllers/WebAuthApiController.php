<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebAuthLoginApiRequest;
use App\Http\Requests\WebAuthRegisterApiRequest;
use App\Services\WebAuthService;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

/**
 * Class WebAuthController
 * @package App\Http\Controllers
 */
class WebAuthApiController extends Controller
{
    /**
     * @var WebAuthService
     */
    protected $service;

    /**
     * WebAuthController constructor.
     * @param WebAuthService $service
     */
    public function __construct(WebAuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @param WebAuthRegisterApiRequest $request
     * @return InertiaResponse
     */
    public function register(WebAuthRegisterApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->service->register($data['name'], $data['password'], $data['email']);
    }

    /**
     * @param WebAuthLoginApiRequest $request
     * @return Response|InertiaResponse
     */
    public function login(WebAuthLoginApiRequest $request)
    {
        $data = $request->validated();
        return $this->service->login($data['name'], $data['password'], $data['remember']);
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        return $this->service->logout();
    }
}
