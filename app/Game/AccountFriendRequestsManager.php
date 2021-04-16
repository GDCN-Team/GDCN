<?php

namespace App\Game;

use App\Models\GameAccount;
use App\Models\GameAccountFriendRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AccountFriendRequestsManager
 * @package App\Game
 */
class AccountFriendRequestsManager
{
    /**
     * @var GameAccount|int
     */
    protected $self;

    /**
     * FriendRequest constructor.
     * @param GameAccount|int $self
     */
    public function __construct($self)
    {
        $this->self = $self->id ?? $self;
    }

    /**
     * @param GameAccount|int $target
     * @return bool
     */
    public function hasInComing($target): bool
    {
        return (bool)$this->getInComing($target);
    }

    /**
     * @param GameAccount|int $target
     * @return GameAccountFriendRequest|Builder|Model|object|null
     */
    public function getInComing($target)
    {
        return GameAccountFriendRequest::query()->where(['account' => $target->id ?? $target, 'to_account' => $this->self])->first();
    }

    /**
     * @param GameAccount|int $target
     * @return bool
     */
    public function hasOutComing($target): bool
    {
        return (bool)$this->getOutComing($target);
    }

    /**
     * @param GameAccount|int $target
     * @return GameAccountFriendRequest|Builder|Model|object|null
     */
    public function getOutComing($target)
    {
        return GameAccountFriendRequest::query()->where(['account' => $this->self, 'to_account' => $target->id ?? $target])->first();
    }

    /**
     * @return int
     */
    public function count_new(): int
    {
        return GameAccountFriendRequest::query()->where(['to_account' => $this->self, 'new' => true])->count();
    }
}
