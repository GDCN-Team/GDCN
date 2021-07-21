<?php

namespace App\Models\Game\Account;

use Database\Factories\Game\Account\BlockFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Block
 *
 * @package App\Models\Game\Account
 * @property int $id
 * @property int $account
 * @property int $target_account
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static BlockFactory factory(...$parameters)
 * @method static Builder|Block newModelQuery()
 * @method static Builder|Block newQuery()
 * @method static Builder|Block query()
 * @method static Builder|Block whereAccount($value)
 * @method static Builder|Block whereCreatedAt($value)
 * @method static Builder|Block whereId($value)
 * @method static Builder|Block whereTargetAccount($value)
 * @method static Builder|Block whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Block extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_blocks';
}
