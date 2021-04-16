<?php

namespace App\Game;

use App\Models\GameAccount;
use App\Models\GameAccountMessage;

/**
 * Class AccountMessagesManager
 * @package App\Game
 */
class AccountMessagesManager
{
    /**
     * @var GameAccount|int
     */
    protected $self;

    /**
     * Message constructor.
     * @param GameAccount|int $self
     */
    public function __construct($self)
    {
        $this->self = $self->id ?? $self;
    }

    /**
     * @return int
     */
    public function count_new(): int
    {
        return GameAccountMessage::query()->where(['to_account' => $this->self, 'readed' => false])->count();
    }
}
