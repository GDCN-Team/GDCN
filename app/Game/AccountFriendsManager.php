<?php

namespace App\Game;

use App\Models\GameAccount;
use App\Models\GameAccountFriend;

/**
 * Class AccountFriendsManager
 * @package App\Game
 */
class AccountFriendsManager
{
    /**
     * @var GameAccount|int
     */
    protected $self;

    /**
     * @var GameAccountFriend
     */
    protected $model;

    /**
     * Friend constructor.
     * @param GameAccount|int $self
     * @param GameAccountFriend $model
     */
    public function __construct($self, GameAccountFriend $model)
    {
        $this->self = $self->id ?? $self;
        $this->model = $model;
    }

    /**
     * @param $target
     * @return bool
     */
    public function has($target): bool
    {
        return $this->model->findEach($this->self, $target)->exists();
    }

    /**
     * @return int
     */
    public function count_new(): int
    {
        return GameAccountFriend::query()->where(['account' => $this->self, 'new' => true])->orWhere(['target_account' => $this->self])->where(['target_new' => true])->count();
    }
}
