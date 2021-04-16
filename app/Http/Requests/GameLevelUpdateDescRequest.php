<?php

namespace App\Http\Requests;

use App\Exceptions\GameUserNotFoundException;
use App\Models\GameAccount;
use App\Models\GameLevel;
use App\Models\GameUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class GameLevelUpdateDescRequest extends GameRequest
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
        } catch (GameUserNotFoundException $e) {
            return false;
        }

        try {
            $this->level = GameLevel::whereId($this->levelID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
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
            'gameVersion' => [
                'required',
                'gte:21'
            ],
            'binaryVersion' => 'required_with:gameVersion',
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'sometimes',
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_without:accountID,gjp',
            'uuid' => 'required_with:udid',
            'levelID' => 'nullable',
            'levelDesc' => 'nullable',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
