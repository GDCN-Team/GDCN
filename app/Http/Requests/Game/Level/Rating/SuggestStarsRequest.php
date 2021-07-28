<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;

class SuggestStarsRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->validateAccountGJP();
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
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => 'required',
            'levelID' => Rule::exists(Level::class, 'id'),
            'stars' => 'between:1,10',
            'feature' => 'boolean',
            'secret' => Rule::in('Wmfp3879gc3')
        ];
    }
}
