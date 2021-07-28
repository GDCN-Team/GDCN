<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;

class ScoreGetRequest extends Request
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
            'percent' => 'between:0,100',
            'secret' => Rule::in('Wmfd2893gb7'),
            'type' => Rule::in([0, 1, 2]),
            's1' => 'gte:8354', // User's attempts + 8354
            's2' => 'gte:3991', // User's jumps + 3991
            's3' => 'gte:4085', // User's time in seconds + 4085
            's4' => 'required', // related to percentage -> Client does math on it (likely used to make the leaderboards accurate)
            's5' => 'required', // Random number goes up to 4 digits
            's6' => 'nullable', // List of PB differences (For example from 0 to 50, then 69, it would be 50,19) XOR'd with 41274 and Base64 encoded
            's7' => 'required', // Randomly Generated 10 character string
            's8' => 'required', // Attempt Count
            's9' => 'gte:5819', // The amount of coins the user got + 5819
            's10' => 'required', // Timely ID
            'chk' => 'required_with:s7'
        ];
    }
}
