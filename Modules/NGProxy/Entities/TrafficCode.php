<?php

namespace Modules\NGProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\NGProxy\Database\factories\TrafficCodeFactory;

/**
 * Modules\NGProxy\Entities\TrafficCode
 *
 * @property int $id
 * @property string $active_code
 * @property string $traffic_count
 * @property int $used
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static TrafficCodeFactory factory(...$parameters)
 * @method static Builder|TrafficCode newModelQuery()
 * @method static Builder|TrafficCode newQuery()
 * @method static Builder|TrafficCode query()
 * @method static Builder|TrafficCode whereActiveCode($value)
 * @method static Builder|TrafficCode whereCreatedAt($value)
 * @method static Builder|TrafficCode whereId($value)
 * @method static Builder|TrafficCode whereTrafficCount($value)
 * @method static Builder|TrafficCode whereUpdatedAt($value)
 * @method static Builder|TrafficCode whereUsed($value)
 * @mixin Eloquent
 */
class TrafficCode extends Model
{
    use HasFactory;

    protected $table = 'ngproxy_traffic_codes';
    protected $fillable = [];

    /**
     * @return TrafficCodeFactory
     */
    protected static function newFactory(): TrafficCodeFactory
    {
        return TrafficCodeFactory::new();
    }
}
