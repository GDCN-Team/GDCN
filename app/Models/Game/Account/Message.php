<?php

namespace App\Models\Game\Account;

use App\Models\Game\Account;
use Database\Factories\Game\Account\MessageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Message
 *
 * @package App\Models\Game\Account
 * @property int $id
 * @property int $account
 * @property int $to_account
 * @property string $subject
 * @property string $body
 * @property int $readed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account $sender
 * @method static MessageFactory factory(...$parameters)
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereAccount($value)
 * @method static Builder|Message whereBody($value)
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereReaded($value)
 * @method static Builder|Message whereSubject($value)
 * @method static Builder|Message whereToAccount($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Message extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_account_messages';

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
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'id', 'account');
    }
}
