<?php

namespace App\Models\Game\Level;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Report
 *
 * @package App\Models\Game\Level
 * @property int $id
 * @property int $level
 * @property int $times
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Report newModelQuery()
 * @method static Builder|Report newQuery()
 * @method static Builder|Report query()
 * @method static Builder|Report whereCreatedAt($value)
 * @method static Builder|Report whereId($value)
 * @method static Builder|Report whereLevel($value)
 * @method static Builder|Report whereTimes($value)
 * @method static Builder|Report whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Report extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_level_reports';
}
