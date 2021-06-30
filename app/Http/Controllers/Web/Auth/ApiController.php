<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginApiRequest;
use App\Http\Requests\Web\Auth\RegisterApiRequest;
use App\Services\Web\AuthService;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

/**
 * Class ApiController
 * @package App\Http\Controllers\Web\Auth
 */
class ApiController extends Controller
{
    /**
     * @var AuthService
     */
    protected $service;

    /**
     * WebAuthController constructor.
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @param RegisterApiRequest $request
     * @return InertiaResponse
     */
    public function register(RegisterApiRequest $request): InertiaResponse
    {
        $data = $request->validated();
        return $this->service->register($data['name'], $data['password'], $data['email']);
    }

    /**
     * @param LoginApiRequest $request
     * @return Response|InertiaResponse
     */
    public function login(LoginApiRequest $request)
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
