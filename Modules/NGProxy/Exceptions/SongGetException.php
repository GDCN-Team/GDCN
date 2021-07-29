<?php

namespace Modules\NGProxy\Exceptions;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Request;

/**
 * Class SongGetException
 * @package Modules\NGProxy\Exceptions
 */
class SongGetException extends Exception
{
    /**
     * @var int
     */
    public int $failedCode = ResponseCode::FAILED;

    /**
     * @return int|array
     */
    public function render(): int|array
    {
        if (Request::isXmlHttpRequest() || Request::expectsJson()) {
            return [
                'status' => false,
                'msg' => __('ngproxy::errors.get')
            ];
        }

        return $this->failedCode;
    }
}
