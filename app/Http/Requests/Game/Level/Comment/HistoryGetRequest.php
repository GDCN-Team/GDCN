<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use App\Models\GameUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class HistoryGetRequest extends Request
{
    /**
     * @var GameAccount
     */
    public $target;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->userID)) {
            return false;
        }

        try {
            $this->target = GameAccount::whereId(GameUser::whereId($this->userID)->value('uuid'))->firstOrFail();
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
            'page' => 'required',
            'total' => 'required_with:page',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'mode' => [
                'required',
                Rule::in([0, 1])
            ],
            'userID' => [
                'required',
                Rule::exists(GameUser::class, 'id')
            ],
            'count' => [
                'sometimes',
                'required'
            ]
        ];
    }
}
