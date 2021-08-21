<?php

namespace App\Models\Game\Account;

use App\Casts\Base64UrlCast;
use App\Models\Game\Account;
use Database\Factories\Game\Account\MessageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game\Account\Message
 *
 * @property int $id
 * @property Account $account
 * @property Account $to_account
 * @property string $subject
 * @property string $body
 * @property int $readed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    protected $table = 'game_account_messages';

    protected $casts = [
        'subject' => Base64UrlCast::class,
        'body' => Base64UrlCast::class
    ];

    protected $fillable = ['to_account', 'subject', 'body', 'readed'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account');
    }

    public function to_account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account');
    }
}
