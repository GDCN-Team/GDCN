<?php

namespace App\Admin\Repositories;

use App\Models\GameLevelGauntlet as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class GameLevelGauntlet extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
