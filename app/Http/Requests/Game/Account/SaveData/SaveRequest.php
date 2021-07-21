<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class SaveRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->validateAccount();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'userName' => Rule::exists(Account::class, 'name'),
            'password' => 'required',
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'saveData' => 'required',
            'secret' => Rule::in('Wmfv3899gc9')
        ];
    }
}
