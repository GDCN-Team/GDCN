<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Exceptions\Game\ChkValidateException;
use App\Exceptions\Game\UserNotFoundException;
use App\Http\Controllers\Game\HashesController;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use App\Models\GameLevel;
use App\Models\GameUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use function app;

class RateStarsRequest extends Request
{
    /**
     * @var GameUser
     */
    public $user;

    /**
     * @var GameLevel
     */
    public $level;

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

        try {
            $this->user = $this->getGameUser();
            $this->level = GameLevel::whereId($this->levelID)->firstOrFail();
        } catch (UserNotFoundException | ModelNotFoundException $e) {
            return false;
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
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'required',
                'exclude_if:accountID,0',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required',
            'uuid' => 'required_with:udid',
            'levelID' => [
                'required',
                Rule::exists(GameLevel::class, 'id')
            ],
            'stars' => [
                'required',
                'digits_between:1,10'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'rs' => 'required',
            'chk' => 'required_with:rs'
        ];
    }

    /**
     * @throws \App\Exceptions\Game\ChkValidateException
     */
    public function validateChk(): void
    {
        $hash = app(HashesController::class);
        $data = $this->validated();

        $hash->checkChk(
            $hash->generateRateChk($data['levelID'], $data['stars'], $data['rs'], $data['accountID'] ?? 0, $data['udid'], $data['uuid']),
            $hash->decodeChk($data['chk'], $hash->keys['rate'])
        );
    }
}
