<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use App\Models\GameLevel;
use App\Models\GameLevelComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class DeleteRequest extends Request
{
    /**
     * @var GameAccount
     */
    public $account;

    /**
     * @var GameLevelComment
     */
    public $comment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->commentID) || empty($this->levelID)) {
            return false;
        }

        try {
            $this->auth();
            $this->account = $this->user();
        } catch (AuthenticationException $e) {
            return false;
        }

        try {
            $this->comment = GameLevelComment::whereId($this->commentID)->firstOrFail();
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
                Rule::exists(GameLevelComment::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'levelID' => [
                'required',
                Rule::exists(GameLevel::class, 'id')
            ]
        ];
    }
}
