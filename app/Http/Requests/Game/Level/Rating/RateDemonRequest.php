<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class RateDemonRequest extends Request
{
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
            $this->level = Level::whereId($this->levelID)->firstOrFail();
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
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => [
                'sometimes',
                'required',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_without_all:accountID,gjp',
            'uuid' => 'required_with:udid',
            'levelID' => [
                'required',
                Rule::exists(Level::class, 'id')
            ],
            'rating' => [
                'required',
                'digits_between:1,5'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfp3879gc3')
            ]
        ];
    }
}
