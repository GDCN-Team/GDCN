<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use App\Models\GameAccountComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

/**
 * Class GameAccountCommentDeleteRequest
 * @package App\Http\Requests
 */
class GameAccountCommentDeleteRequest extends GameRequest
{
    /**
     * @var GameAccount
     */
    public $account;

    /**
     * @var GameAccountComment
     */
    public $comment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->commentID)) {
            return false;
        }

        try {
            $this->auth();
            $this->account = $this->user();
        } catch (GameAuthenticationException $e) {
            return false;
        }

        try {
            $this->comment = GameAccountComment::whereId($this->commentID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        if (!$this->account->can('delete', $this->comment)) {
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
            'gameVersion' => [
                'required',
                'gte:21'
            ],
            'binaryVersion' => 'required_with:gameVersion',
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'commentID' => [
                'required',
                Rule::exists(GameAccountComment::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'cType' => [
                'required',
                Rule::in(1)
            ]
        ];
    }
}
