<?php

namespace App\Exceptions\Game;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Log as LogFacade;

class LevelUploadException extends Exception
{
    /**
     * @var int
     */
    public int $failed_code = ResponseCode::LEVEL_UPLOAD_FAILED;
    public string $level_name;

    public function report()
    {
        LogFacade::channel('gdcn')
            ->notice('[Level] Action: Upload Level Failed', ['levelName' => $this->level_name, 'reason' => $this->message]);
    }

    /**
     * @return array|int
     */
    public function render(): int|array
    {
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
