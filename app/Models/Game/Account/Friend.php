<?php

namespace App\Models\Game\Account;

use App\Models\Game\Account as AccountModel;
use Database\Factories\Game\Account\FriendFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Friend
 *
 * @package App\Models\Game\Account
 * @property int $id
 * @property int $account
 * @property int $target_account
 * @property int $new
 * @property int $target_new
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FriendFactory factory(...$parameters)
 * @method static Builder|Friend newModelQuery()
 * @method static Builder|Friend newQuery()
 * @method static Builder|Friend query()
 * @method static Builder|Friend whereAccount($value)
 * @method static Builder|Friend whereCreatedAt($value)
 * @method static Builder|Friend whereId($value)
 * @method static Builder|Friend whereNew($value)
 * @method static Builder|Friend whereTargetAccount($value)
 * @method static Builder|Friend whereTargetNew($value)
 * @method static Builder|Friend whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Friend extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_friends';

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
