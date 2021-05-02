<?php

namespace App\Models;

use Database\Factories\GameAccountMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class GameAccountMessage
 *
 * @package App\Models
 * @property int $id
 * @property int $account
 * @property int $to_account
 * @property string $subject
 * @property string $body
 * @property int $readed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameAccountMessage newModelQuery()
 * @method static Builder|GameAccountMessage newQuery()
 * @method static Builder|GameAccountMessage query()
 * @method static Builder|GameAccountMessage whereAccount($value)
 * @method static Builder|GameAccountMessage whereBody($value)
 * @method static Builder|GameAccountMessage whereCreatedAt($value)
 * @method static Builder|GameAccountMessage whereId($value)
 * @method static Builder|GameAccountMessage whereReaded($value)
 * @method static Builder|GameAccountMessage whereSubject($value)
 * @method static Builder|GameAccountMessage whereToAccount($value)
 * @method static Builder|GameAccountMessage whereUpdatedAt($value)
 * @mixin Model
 * @property-read GameAccount $sender
 * @method static GameAccountMessageFactory factory(...$parameters)
 */
class GameAccountMessage extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account',
        'to_account',
        'subject',
        'body'
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
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(GameAccount::class, 'id', 'account');
    }
}
