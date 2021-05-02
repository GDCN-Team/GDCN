<?php

namespace App\Models;

use App\Models\GameAccount as AccountModel;
use Database\Factories\GameAccountFriendFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountFriend
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property int $target_account
 * @property int $new
 * @property int $target_new
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountFriend newModelQuery()
 * @method static Builder|GameAccountFriend newQuery()
 * @method static Builder|GameAccountFriend query()
 * @method static Builder|GameAccountFriend whereAccount($value)
 * @method static Builder|GameAccountFriend whereCreatedAt($value)
 * @method static Builder|GameAccountFriend whereId($value)
 * @method static Builder|GameAccountFriend whereNew($value)
 * @method static Builder|GameAccountFriend whereTargetAccount($value)
 * @method static Builder|GameAccountFriend whereTargetNew($value)
 * @method static Builder|GameAccountFriend whereUpdatedAt($value)
 * @mixin Model
 * @method static GameAccountFriendFactory factory(...$parameters)
 */
class GameAccountFriend extends Model
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

    /**
     * @param int|AccountModel $account1
     * @param int|AccountModel $account2
     * @return Builder
     */
    public function findEach(int $account1, int $account2): Builder
    {
        return self::query()->where(['account' => $account1->id ?? $account1, 'target_account' => $account2->id ?? $account2])->orWhere('target_account', $account1->id ?? $account1)->where('account', $account2->id ?? $account2);
    }
}
