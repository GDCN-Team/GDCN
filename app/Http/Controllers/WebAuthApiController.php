<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebAuthLoginApiRequest;
use App\Http\Requests\WebAuthRegisterApiRequest;
use App\Presenter\WebAuthPresenter;
use App\Services\WebAuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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
     * @var WebAuthPresenter
     */
    protected $presenter;

    /**
     * WebAuthController constructor.
     * @param WebAuthPresenter $presenter
     * @param WebAuthService $service
     */
    public function __construct(WebAuthPresenter $presenter, WebAuthService $service)
    {
        $this->presenter = $presenter;
        $this->service = $service;
    }

    public function register(WebAuthRegisterApiRequest $request)
    {
        $data = $request->validated();
        return $this->service->register($data['name'], $data['password'], $data['email']);
    }

    /**
     * @param WebAuthLoginApiRequest $request
     * @return Response|\Inertia\Response
     */
    public function login(WebAuthLoginApiRequest $request)
    {
        $data = $request->validated();
        if ($this->service->login($data['name'], $data['password'])) {
            $url = Session::pull('url.intended', '/');
            return Inertia::location($url);
        }

        return $this->presenter->login(['errors' => ['password' => '密码错误']]);
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        Auth::logout();

        $url = Session::pull('url.intended', '/');
        return Inertia::location($url);
    }
}
