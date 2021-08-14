<?php

namespace App\Presenters\Web;

use Inertia\Inertia;
use Inertia\Response;

class AuthPresenter
{
    /**
     * @param array $props
     * @return Response
     */
    public function renderLoginPage(array $props = []): Response
    {
        return Inertia::render('Auth/Login', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderPasswordConfirmPage(array $props = []): Response
    {
        return Inertia::render('Auth/PasswordConfirm', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderRegisterPage(array $props = []): Response
    {
        return Inertia::render('Auth/Register', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderForgotPasswordPage(array $props = []): Response
    {
        return Inertia::render('Auth/ForgotPassword', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderForgotNamePage(array $props = []): Response
    {
        return Inertia::render('Auth/ForgotUserName', $props);
    }

    /**
     * @param array $props
     * @return Response
     */
    public function renderPasswordResetPage(array $props = []): Response
    {
        return Inertia::render('Auth/PasswordReset', $props);
    }
}
