<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Notice
 *
 * @package App\Models
 * @method static Builder|Notice newModelQuery()
 * @method static Builder|Notice newQuery()
 * @method static Builder|Notice query()
 * @mixin Eloquent
 * @property int $id
 * @property string $notice
 * @property int $account
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Notice whereAccount($value)
 * @method static Builder|Notice whereCreatedAt($value)
 * @method static Builder|Notice whereId($value)
 * @method static Builder|Notice whereNotice($value)
 * @method static Builder|Notice whereUpdatedAt($value)
 */
class Notice extends Model
{
    use HasFactory;
}
