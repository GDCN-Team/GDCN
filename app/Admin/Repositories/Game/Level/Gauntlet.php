<?php

namespace App\Admin\Repositories\Game\Level;

use App\Models\Game\Level\Gauntlet as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Gauntlet extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
