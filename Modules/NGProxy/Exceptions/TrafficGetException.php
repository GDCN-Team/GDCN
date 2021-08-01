<?php

namespace Modules\NGProxy\Exceptions;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Request;

class TrafficGetException extends Exception
{
    /**
     * @var int
     */
    public int $failedCode = ResponseCode::SONG_GET_FAILED;

    /**
     * @return int|array
     */
    public function render(): int|array
    {
        if (Request::isXmlHttpRequest() || Request::expectsJson()) {
            return [
                'status' => false,
                'msg' => __('ngproxy::errors.traffic')
            ];
        }

        return $this->failedCode;
    }
}
