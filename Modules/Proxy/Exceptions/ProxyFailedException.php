<?php

namespace Modules\Proxy\Exceptions;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Request;

/**
 * Class ProxyFailedException
 * @package Modules\Proxy\Exceptions
 */
class ProxyFailedException extends Exception
{
    /**
     * @var int
     */
    public int $failedCode = ResponseCode::FAILED;

    /**
     * @return int|array
     */
    public function response(): int|array
    {
        if (Request::isXmlHttpRequest()) {
            return [
                'status' => false,
                'msg' => __('proxy::errors.proxy_failed')
            ];
        }

        return $this->failedCode;
    }
}
