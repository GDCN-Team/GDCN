<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountBlock
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property int $target_account
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountBlock newModelQuery()
 * @method static Builder|GameAccountBlock newQuery()
 * @method static Builder|GameAccountBlock query()
 * @method static Builder|GameAccountBlock whereAccount($value)
 * @method static Builder|GameAccountBlock whereCreatedAt($value)
 * @method static Builder|GameAccountBlock whereId($value)
 * @method static Builder|GameAccountBlock whereTargetAccount($value)
 * @method static Builder|GameAccountBlock whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountBlock extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'target_account'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'account' => 'integer',
        'target_account' => 'integer'
    ];
}
