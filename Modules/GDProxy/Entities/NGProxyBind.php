<?php

namespace Modules\GDProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\GDProxy\Database\factories\NGProxyBindFactory;

/**
 * Modules\GDProxy\Entities\NGProxyBind
 *
 * @property int $id
 * @property int $account_id
 * @property int $ngproxy_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static NGProxyBindFactory factory(...$parameters)
 * @method static Builder|NGProxyBind newModelQuery()
 * @method static Builder|NGProxyBind newQuery()
 * @method static Builder|NGProxyBind query()
 * @method static Builder|NGProxyBind whereAccountId($value)
 * @method static Builder|NGProxyBind whereCreatedAt($value)
 * @method static Builder|NGProxyBind whereId($value)
 * @method static Builder|NGProxyBind whereNgproxyUserId($value)
 * @method static Builder|NGProxyBind whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $account_name
 * @method static Builder|NGProxyBind whereAccountName($value)
 */
class NGProxyBind extends Model
{
    use HasFactory;

    protected $table = 'gdproxy_ngproxy_binds';
    protected $fillable = [];

    /**
     * @return NGProxyBindFactory
     */
    protected static function newFactory(): NGProxyBindFactory
    {
        return NGProxyBindFactory::new();
    }
}
