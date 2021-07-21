<?php

namespace App\Http\Requests\Game\Level;

use App\Enums\Game\Log\Types;
use App\Game\Components\Hash\Checker;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Daily;
use App\Models\Game\Level\Weekly;
use App\Models\Game\Log;
use App\Models\Game\User;
use GDCN\ChkValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use function app;
use function config;

class DownloadRequest extends Request
{
    /**
     * Daily id or weekly id
     *
     * @var int
     */
    public $feaID = 0;

    /**
     * @var Level
     */
    public $level;

    /**
     * @var User
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
            case -1: // Daily
                $daily = Daily::query()->latest();
                $this->feaID = $daily->id;
                $this->level = Level::whereId($daily->level)->first();
                break;
            case -2: // Weekly
                $weekly = Weekly::query()->latest();
                $this->feaID = $weekly->id + config('game.weeklyIdOffset', 100000);
                $this->level = Level::whereId($weekly->level)->first();
                break;
            default:
                try {
                    $this->level = Level::whereId($this->levelID)->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return false;
                }
                break;
        }

        $attributes = [
            'type' => Types::DOWNLOADED_LEVEL,
            'value' => $this->level->id,
            'ip' => $this->ip()
        ];

        $log = Log::query()
            ->where($attributes);

        if (!$log->exists()) {
            $log->create($attributes);
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
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_with:gjp',
            'uuid' => 'required_with:udid',
            'levelID' => [
                'required',
                Rule::exists(Level::class, 'id')
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
