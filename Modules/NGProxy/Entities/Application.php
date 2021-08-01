<?php

namespace Modules\NGProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\NGProxy\Database\factories\ApplicationFactory;

/**
 * Modules\NGProxy\Entities\Application
 *
 * @property int $id
 * @property string $app_id
 * @property string $app_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static ApplicationFactory factory(...$parameters)
 * @method static Builder|Application newModelQuery()
 * @method static Builder|Application newQuery()
 * @method static Builder|Application query()
 * @method static Builder|Application whereAppId($value)
 * @method static Builder|Application whereAppName($value)
 * @method static Builder|Application whereCreatedAt($value)
 * @method static Builder|Application whereId($value)
 * @method static Builder|Application whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Application extends Model
{
    use HasFactory;

    protected $table = 'ngproxy_applications';
    protected $fillable = [];

    /**
     * @return ApplicationFactory
     */
    protected static function newFactory(): ApplicationFactory
    {
        return ApplicationFactory::new();
    }
}
