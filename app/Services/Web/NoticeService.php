<?php

namespace App\Services\Web;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

/**
 * Class NoticeService
 * @package App\Services
 */
class NoticeService
{
    /**
     * @param $message
     * @param null $description
     */
    public function sendErrorNotice($message, $description = null): void
    {
        Session::push('notices', [
            'type' => 'error',
            'message' => $message,
            'description' => $description
        ]);

        $this->loadNotices();
    }

    /**
     * @param $message
     * @param null $description
     */
    public function sendInfoNotice($message, $description = null): void
    {
        Session::push('notices', [
            'type' => 'info',
            'message' => $message,
            'description' => $description
        ]);

        $this->loadNotices();
    }

    /**
     * @param $message
     * @param null $description
     */
    public function sendSuccessNotice($message, $description = null): void
    {
        Session::push('notices', [
            'type' => 'success',
            'message' => $message,
            'description' => $description
        ]);

        $this->loadNotices();
    }

    protected function loadNotices(): void
    {
        Inertia::share('notices', Session::pull('notices'));
    }
}
