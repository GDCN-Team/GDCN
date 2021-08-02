<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use App\Http\Controllers\Web\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Request;

class LevelUploadException extends Exception
{
    use ResponseTrait;

    /**
     * @var int
     */
    public int $failed_code = ResponseCode::LEVEL_UPLOAD_FAILED;
    public string $level_name;

    public function report()
    {
        LogFacade::channel('gdcn')
            ->notice('[Level]上传失败', ['levelName' => $this->level_name, 'reason' => $this->message]);
    }

    /**
     * @return array|int
     */
    public function render(): int|array
    {
        if (Request::isXmlHttpRequest() || Request::expectsJson()) {
            return $this->response(false, $this->message);
        }

        return $this->failed_code;
    }

    /**
     * @param string $levelName
     * @return LevelUploadException
     */
    public function setLevelName(string $levelName): static
    {
        $this->level_name = $levelName;
        return $this;
    }
}
