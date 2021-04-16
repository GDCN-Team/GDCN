<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountLink
 *
 * @package App\Models
 * @property int $id
 * @property string $host
 * @property int $account
 * @property int $target_account_id
 * @property int $target_user_id
 * @property string $target_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountLink newModelQuery()
 * @method static Builder|GameAccountLink newQuery()
 * @method static Builder|GameAccountLink query()
 * @method static Builder|GameAccountLink whereAccount($value)
 * @method static Builder|GameAccountLink whereCreatedAt($value)
 * @method static Builder|GameAccountLink whereHost($value)
 * @method static Builder|GameAccountLink whereId($value)
 * @method static Builder|GameAccountLink whereTargetAccountId($value)
 * @method static Builder|GameAccountLink whereTargetName($value)
 * @method static Builder|GameAccountLink whereTargetUserId($value)
 * @method static Builder|GameAccountLink whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountLink extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'host',
        'account',
        'target_name',
        'target_account_id',
        'target_user_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'account' => 'integer'
    ];
}
