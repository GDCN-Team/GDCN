<?php

namespace App\Http\Requests;

use App\Enums\GameLogType;
use App\Enums\GameSpecialLevelID;
use App\Game\Components\Hash\Checker;
use App\Models\GameAccount;
use App\Models\GameDailyLevel;
use App\Models\GameLevel;
use App\Models\GameLog;
use App\Models\GameUser;
use App\Models\GameWeeklyLevel;
use GDCN\ChkValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class GameLevelDownloadRequest extends GameRequest
{
    /**
     * Daily id or weekly id
     *
     * @var int
     */
    public $feaID = 0;

    /**
     * @var GameLevel
     */
    public $level;

    /**
     * @var GameUser
     */
    public $user;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->levelID)) {
            return false;
        }

        switch ($this->levelID) {
            case GameSpecialLevelID::DAILY:
                $daily = GameDailyLevel::query()->latest();
                $this->feaID = $daily->id;
                $this->level = GameLevel::whereId($daily->level)->first();
                break;
            case GameSpecialLevelID::WEEKLY:
                $weekly = GameWeeklyLevel::query()->latest();
                $this->feaID = $weekly->id + config('game.weeklyIdOffset', 100000);
                $this->level = GameLevel::whereId($weekly->level)->first();
                break;
            default:
                try {
                    $this->level = GameLevel::whereId($this->levelID)->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return false;
                }
                break;
        }

        $logCreated = app(GameLog::class)
            ->existsOrNew(
                GameLogType::fromValue(GameLogType::DOWNLOADED_LEVEL),
                $this->level->id,
                null, true);

        if ($logCreated) {
            $this->level->increment('downloads');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'gte:20'
            ],
            'binaryVersion' => 'required_with:gameVersion',
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'sometimes',
                'required',
                'exclude_if:accountID,0',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_with:gjp',
            'uuid' => 'required_with:udid',
            'levelID' => [
                'required',
                Rule::exists(GameLevel::class, 'id')
            ],
            'inc' => 'required',
            'extras' => 'required',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'rs' => [
                'sometimes',
                'required'
            ],
            'chk' => 'required_with:rs'
        ];
    }

    /**
     * @throws ChkValidationException
     */
    public function validateChk(): void
    {
        if (empty($this->chk)) {
            return;
        }

        app(Checker::class)->downloadLevel($this);
    }
}
