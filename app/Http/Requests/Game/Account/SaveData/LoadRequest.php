<?php

namespace App\Http\Requests\Game\Account\SaveData;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class LoadRequest extends Request
{
    /**
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
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'userName' => Rule::exists(Account::class, 'name'),
            'password' => 'required',
            'secret' => Rule::in('Wmfv3899gc9')
        ];
    }
}
