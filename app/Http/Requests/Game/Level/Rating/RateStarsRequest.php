<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;

class RateStarsRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->filled(['accountID', 'gjp'])) {
            return $this->validateAccountGJP();
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
                'exclude_if:accountID,0',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required',
            'uuid' => 'required',
            'levelID' => Rule::exists(Level::class, 'id'),
            'stars' => 'between:1,10',
            'secret' => Rule::in('Wmfd2893gb7'),
            'rs' => 'required',
            'chk' => 'required_with:rs'
        ];
    }
}
