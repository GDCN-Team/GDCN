<?php

namespace App\Http\Controllers\Web\Traits;

/**
 * Trait ResponseTrait
 * @package App\Http\Controllers\Web\Traits
 */
trait ResponseTrait
{
    /**
     * @param bool $status
     * @param string|null $msg
     * @param null $data
     * @return array
     */
    protected function response(bool $status, ?string $msg = null, $data = null): array
    {
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
