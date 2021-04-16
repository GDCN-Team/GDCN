<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountFriendRequest
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property int $to_account
 * @property string|null $comment
 * @property int $new
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountFriendRequest newModelQuery()
 * @method static Builder|GameAccountFriendRequest newQuery()
 * @method static Builder|GameAccountFriendRequest query()
 * @method static Builder|GameAccountFriendRequest whereAccount($value)
 * @method static Builder|GameAccountFriendRequest whereComment($value)
 * @method static Builder|GameAccountFriendRequest whereCreatedAt($value)
 * @method static Builder|GameAccountFriendRequest whereId($value)
 * @method static Builder|GameAccountFriendRequest whereNew($value)
 * @method static Builder|GameAccountFriendRequest whereToAccount($value)
 * @method static Builder|GameAccountFriendRequest whereUpdatedAt($value)
 * @mixin Model
 */
class GameAccountFriendRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'to_account',
        'comment'
    ];

    /**
     * @param int $account1
     * @param int $account2
     * @return Builder
     */
    public function findEach(int $account1, int $account2): Builder
    {
        return self::query()->where(['account' => $account1->id ?? $account1, 'to_account' => $account2->id ?? $account2])->orWhere('to_account', $account1->id ?? $account1)->where('account', $account2->id ?? $account2);
    }

    /**
     * @param $isSender
     * @return GameAccount|GameAccount[]|Builder|Builder[]|Collection|Model|mixed|null
     */
    public function getTarget($isSender)
    {
        $accountID = !$isSender ? $this->account : $this->to_account;
        return GameAccount::query()->find($accountID);
    }

    /**
     * @return bool
     */
    public function accept(): bool
    {
        try {
            $this->delete();
        } catch (Exception $e) {
            return false;
        }

        GameAccountFriend::query()
            ->firstOrCreate([
                'account' => $this->account,
                'target_account' => $this->to_account
            ]);

        return true;
    }
}
