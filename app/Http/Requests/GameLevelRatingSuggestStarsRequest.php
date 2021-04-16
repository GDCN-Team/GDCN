<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use App\Models\GameLevel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class GameLevelRatingSuggestStarsRequest extends GameRequest
{
    /**
     * @var GameAccount
     */
    public $account;

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
            $this->auth();
            $this->account = $this->user();
            $this->level = GameLevel::whereId($this->levelID)->firstOrFail();
        } catch (GameAuthenticationException | ModelNotFoundException $e) {
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
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'levelID' => [
                'required',
                Rule::exists(GameLevel::class, 'id')
            ],
            'stars' => [
                'required',
                'digits_between:1,10'
            ],
            'feature' => [
                'required',
                'boolean'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfp3879gc3')
            ]
        ];
    }
}
