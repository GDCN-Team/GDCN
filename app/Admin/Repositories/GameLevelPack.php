<?php

namespace App\Admin\Repositories;

use App\Models\GameLevelPack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class GameLevelPack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
