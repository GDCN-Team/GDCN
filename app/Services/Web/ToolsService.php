<?php

namespace App\Services\Web;

use App\Services\Web\Tools\AccountService;
use App\Services\Web\Tools\LevelService;
use App\Services\Web\Tools\SongService;

class ToolsService
{
    /**
     * @var array|string[][]
     */
    public array $servers = [
        'official' => [
            'alias' => '官服',
            'endpoint' => 'http://www.boomlings.com/database'
        ],
        'GDProxy' => [
            'alias' => '官服(使用GDProxy加速)',
            'name' => 'official',
            'endpoint' => 'https://dl.geometrydashchinese.com'
        ]
    ];

    /**
     * @param NotificationService $notification
     * @param AccountService $account
     * @param LevelService $level
     * @param SongService $song
     */
    public function __construct(
        public NotificationService $notification,
        public AccountService      $account,
        public LevelService        $level,
        public SongService         $song
    )
    {
        $this->account->setServers($this->servers);
        $this->level->setServers($this->servers);
    }
}
