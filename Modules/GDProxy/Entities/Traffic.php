<?php

namespace Modules\GDProxy\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\GDProxy\Database\factories\TrafficFactory;

/**
 * Class Traffic
 *
 * @package Modules\GDProxy\Entities
 * @property int $id
 * @property int $count
 * @property string $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static TrafficFactory factory(...$parameters)
 * @method static Builder|Traffic newModelQuery()
 * @method static Builder|Traffic newQuery()
 * @method static Builder|Traffic query()
 * @method static Builder|Traffic whereCount($value)
 * @method static Builder|Traffic whereCreatedAt($value)
 * @method static Builder|Traffic whereDate($value)
 * @method static Builder|Traffic whereId($value)
 * @method static Builder|Traffic whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Traffic extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'gdproxy_traffics';

    /**
     * @var string[]
     */
    protected $fillable = ['date'];

    /**
     * @return TrafficFactory
     */
    protected static function newFactory(): TrafficFactory
    {
        return TrafficFactory::new();
    }
}
