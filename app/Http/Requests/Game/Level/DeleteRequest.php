<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class DeleteRequest extends Request
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

        if (!$this->user->can('delete', $this->level)) {
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
            'uuid' => 'required_without_all:accountID,gjp',
            'udid' => 'required_with:uuid',
            'levelID' => [
                'required',
                Rule::exists(Level::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfv2898gc9')
            ]
        ];
    }
}
