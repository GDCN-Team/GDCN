<?php

namespace App\Exceptions\Newgrounds;

use Exception;
use Illuminate\Support\Facades\Request;

class SongDisabledException extends Exception
{
    public function response(): array
    {
        if (Request::isXmlHttpRequest()) {
            return [
                'status' => false,
                'msg' => '歌曲已被禁用, 无法查看'
            ];
        }
    }
}
