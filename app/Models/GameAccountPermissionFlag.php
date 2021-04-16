<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountPermissionFlag
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountPermissionFlag newModelQuery()
 * @method static Builder|GameAccountPermissionFlag newQuery()
 * @method static Builder|GameAccountPermissionFlag query()
 * @method static Builder|GameAccountPermissionFlag whereCreatedAt($value)
 * @method static Builder|GameAccountPermissionFlag whereId($value)
 * @method static Builder|GameAccountPermissionFlag whereName($value)
 * @method static Builder|GameAccountPermissionFlag whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountPermissionFlag extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];
}
