<?php

namespace App\Http\Requests\Game\Account\Setting;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
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
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => 'required',
            'mS' => 'between:0,2',
            'frS' => 'between:0,1',
            'cS' => 'between:0,2',
            'yt' => 'nullable',
            'twitter' => 'nullable',
            'twitch' => 'nullable',
            'secret' => Rule::in('Wmfv3899gc9')
        ];
    }
}
