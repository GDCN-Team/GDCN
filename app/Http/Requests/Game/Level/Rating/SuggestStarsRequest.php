<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class SuggestStarsRequest extends Request
{
    /**
     * @var Account
     */
    public $account;

    /**
     * @var Level
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
            $this->level = Level::whereId($this->levelID)->firstOrFail();
        } catch (AuthenticationException | ModelNotFoundException $e) {
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
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'levelID' => [
                'required',
                Rule::exists(Level::class, 'id')
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
