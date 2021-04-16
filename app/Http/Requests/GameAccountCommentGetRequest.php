<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

/**
 * Class GameAccountCommentGetRequest
 * @package App\Http\Requests
 */
class GameAccountCommentGetRequest extends GameRequest
{
    /**
     * @var GameAccount
     */
    public $account;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->accountID)) {
            return false;
        }

        try {
            $this->account = GameAccount::whereId($this->accountID)->firstOrFail();
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
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'page' => [
                'required',
                'integer'
            ],
            'total' => 'required_with:page',
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
