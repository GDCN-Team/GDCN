<?php

namespace App\Admin\Repositories\Game\Level;

use App\Models\Game\Level\Pack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Pack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
