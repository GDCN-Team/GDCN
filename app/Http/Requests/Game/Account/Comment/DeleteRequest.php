<?php

namespace App\Http\Requests\Game\Account\Comment;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use App\Models\GameAccountComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

/**
 * Class DeleteRequest
 * @package App\Http\Requests
 */
class DeleteRequest extends Request
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
        } catch (AuthenticationException $e) {
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
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
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
