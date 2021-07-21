<?php

namespace App\Models\Game;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class ContestInformation
 *
 * @package App\Models\Game
 * @property int $id
 * @property int $contest
 * @property int $account
 * @property int $level
 * @property int $submitted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ContestInformation newModelQuery()
 * @method static Builder|ContestInformation newQuery()
 * @method static Builder|ContestInformation query()
 * @method static Builder|ContestInformation whereAccount($value)
 * @method static Builder|ContestInformation whereContest($value)
 * @method static Builder|ContestInformation whereCreatedAt($value)
 * @method static Builder|ContestInformation whereId($value)
 * @method static Builder|ContestInformation whereLevel($value)
 * @method static Builder|ContestInformation whereSubmitted($value)
 * @method static Builder|ContestInformation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ContestInformation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'game_contest_information';
}
