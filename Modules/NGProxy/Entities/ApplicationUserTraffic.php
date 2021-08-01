<?php

namespace Modules\NGProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\NGProxy\Database\factories\ApplicationUserTrafficFactory;

/**
 * Modules\NGProxy\Entities\ApplicationUserTraffic
 *
 * @property int $id
 * @property int $user_id
 * @property string $traffic_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static ApplicationUserTrafficFactory factory(...$parameters)
 * @method static Builder|ApplicationUserTraffic newModelQuery()
 * @method static Builder|ApplicationUserTraffic newQuery()
 * @method static Builder|ApplicationUserTraffic query()
 * @method static Builder|ApplicationUserTraffic whereCreatedAt($value)
 * @method static Builder|ApplicationUserTraffic whereId($value)
 * @method static Builder|ApplicationUserTraffic whereTrafficCount($value)
 * @method static Builder|ApplicationUserTraffic whereUpdatedAt($value)
 * @method static Builder|ApplicationUserTraffic whereUserId($value)
 * @mixin Eloquent
 */
class ApplicationUserTraffic extends Model
{
    use HasFactory;

    protected $table = 'ngproxy_application_user_traffics';
    protected $fillable = [];

    /**
     * @return ApplicationUserTrafficFactory
     */
    protected static function newFactory(): ApplicationUserTrafficFactory
    {
        return ApplicationUserTrafficFactory::new();
    }
}
