<?php

namespace App\Http\Controllers\Web\Traits;

use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape(['status' => "bool", 'msg' => "null|string", 'data' => "null"])] protected function response(bool $status, ?string $msg = null, $data = null): array
    {
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
