<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class SaveRequest extends Request
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
            'userName' => [
                'required',
                Rule::exists(GameAccount::class, 'name')
            ],
            'password' => [
                'required',
                'password'
            ],
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'saveData' => 'required',
            'secret' => [
                'required',
                Rule::in('Wmfv3899gc9')
            ]
        ];
    }
}
