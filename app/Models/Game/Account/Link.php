<?php

namespace App\Models\Game\Account;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Link
 *
 * @package App\Models\Game\Account
 * @property int $id
 * @property string $host
 * @property int $account
 * @property int $target_account_id
 * @property int $target_user_id
 * @property string $target_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Link newModelQuery()
 * @method static Builder|Link newQuery()
 * @method static Builder|Link query()
 * @method static Builder|Link whereAccount($value)
 * @method static Builder|Link whereCreatedAt($value)
 * @method static Builder|Link whereHost($value)
 * @method static Builder|Link whereId($value)
 * @method static Builder|Link whereTargetAccountId($value)
 * @method static Builder|Link whereTargetName($value)
 * @method static Builder|Link whereTargetUserId($value)
 * @method static Builder|Link whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Link extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_links';
}
