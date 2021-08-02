<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Request;

class SongNotFoundException extends Exception
{
    /**
     * @var int
     */
    public int $failedCode = ResponseCode::SONG_NOT_FOUND;

    /**
     * @return int|array
     */
    public function render(): int|array
    {
        if (Request::isXmlHttpRequest() || Request::expectsJson()) {
            return [
                'status' => false,
                'msg' => __('ngproxy::errors.not_found')
            ];
        }

        return $this->failedCode;
    }
}
