<?php

namespace App\Services\Web;

use Illuminate\Support\Facades\Session;

class NotificationService
{
    /**
     * @param string $type
     * @param string $content
     */
    public function sendMessage(string $type, string $content)
    {
        Session::push(
            'messages',
            compact('type', 'content')
        );
    }
}
