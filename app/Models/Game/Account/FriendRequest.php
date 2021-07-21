<?php

namespace App\Models\Game\Account;

use App\Models\Game\Account;
use Database\Factories\Game\Account\FriendRequestFactory;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class FriendRequest
 *
 * @package App\Models\Game\Account
 * @property int $id
 * @property int $account
 * @property int $to_account
 * @property string|null $comment
 * @property int $new
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FriendRequestFactory factory(...$parameters)
 * @method static Builder|FriendRequest newModelQuery()
 * @method static Builder|FriendRequest newQuery()
 * @method static Builder|FriendRequest query()
 * @method static Builder|FriendRequest whereAccount($value)
 * @method static Builder|FriendRequest whereComment($value)
 * @method static Builder|FriendRequest whereCreatedAt($value)
 * @method static Builder|FriendRequest whereId($value)
 * @method static Builder|FriendRequest whereNew($value)
 * @method static Builder|FriendRequest whereToAccount($value)
 * @method static Builder|FriendRequest whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FriendRequest extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_friend_requests';

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
     * @return Account|Account[]|Builder|Builder[]|Collection|Model|mixed|null
     */
    public function getTarget($isSender)
    {
        $accountID = !$isSender ? $this->account : $this->to_account;
        return Account::query()->find($accountID);
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

        Friend::query()
            ->firstOrCreate([
                'account' => $this->account,
                'target_account' => $this->to_account
            ]);

        return true;
    }
}
