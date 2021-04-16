<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GameContestInformation
 *
 * @package App\Models
 * @property int $id
 * @property int $contest
 * @property int $account
 * @property int $level
 * @property int $submitted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GameContestInformation newModelQuery()
 * @method static Builder|GameContestInformation newQuery()
 * @method static Builder|GameContestInformation query()
 * @method static Builder|GameContestInformation whereAccount($value)
 * @method static Builder|GameContestInformation whereContest($value)
 * @method static Builder|GameContestInformation whereCreatedAt($value)
 * @method static Builder|GameContestInformation whereId($value)
 * @method static Builder|GameContestInformation whereLevel($value)
 * @method static Builder|GameContestInformation whereSubmitted($value)
 * @method static Builder|GameContestInformation whereUpdatedAt($value)
 * @mixin Model
 */
class GameContestInformation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_contest_information';
}
