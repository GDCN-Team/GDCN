<?php

namespace Modules\NGProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\NGProxy\Database\factories\ApplicationUserFactory;

/**
 * Modules\NGProxy\Entities\ApplicationUser
 *
 * @property int $id
 * @property int $app_id
 * @property int $user_id
 * @property string $bind_ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static ApplicationUserFactory factory(...$parameters)
 * @method static Builder|ApplicationUser newModelQuery()
 * @method static Builder|ApplicationUser newQuery()
 * @method static Builder|ApplicationUser query()
 * @method static Builder|ApplicationUser whereAppId($value)
 * @method static Builder|ApplicationUser whereBindIp($value)
 * @method static Builder|ApplicationUser whereCreatedAt($value)
 * @method static Builder|ApplicationUser whereId($value)
 * @method static Builder|ApplicationUser whereUpdatedAt($value)
 * @method static Builder|ApplicationUser whereUserId($value)
 * @mixin Eloquent
 * @property-read ApplicationUserTraffic $traffic
 */
class ApplicationUser extends Model
{
    use HasFactory;

    protected $table = 'ngproxy_application_users';
    protected $fillable = [];

    /**
     * @return ApplicationUserFactory
     */
    protected static function newFactory(): ApplicationUserFactory
    {
        return ApplicationUserFactory::new();
    }

    /**
     * @return HasOne
     */
    public function traffic(): HasOne
    {
        return $this->hasOne(ApplicationUserTraffic::class, 'user_id')->withDefault([
            'user_id' => $this->id,
            'traffic_count' => 0
        ]);
    }
}
