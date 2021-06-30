<?php

namespace App\Http\Requests\Game\Level;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use App\Models\GameLevel;
use Illuminate\Validation\Rule;

class ScoreGetRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            return $this->auth();
        } catch (AuthenticationException $e) {
            return false;
        }
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
            'percent' => [
                'required',
                'digits_between:0,100'
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'type' => [
                'required',
                Rule::in([0, 1, 2])
            ],
            's1' => [
                'required',
                'numeric',
                'min:8354',
            ],
            's2' => [
                'required',
                'numeric',
                'min:3991'
            ],
            's3' => [
                'required',
                'numeric',
                'min:4085'
            ],
            's4' => 'required',
            's5' => 'required',
            's6' => 'nullable',
            's7' => 'required',
            's8' => 'required',
            's9' => [
                'required',
                'numeric',
                'min:5819'
            ],
            's10' => 'required',
            'chk' => 'required_with:s7'
        ];
    }
}
