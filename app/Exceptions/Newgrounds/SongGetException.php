<?php

namespace App\Exceptions\Newgrounds;

use Exception;
use Illuminate\Support\Facades\Request;

class SongGetException extends Exception
{
    public function response(): array
    {
        if (Request::isXmlHttpRequest()) {
            return [
                'status' => false,
                'msg' => '歌曲获取失败'
            ];
        }

        abort(404, '歌曲不存在(或未找到)');
    }
}
