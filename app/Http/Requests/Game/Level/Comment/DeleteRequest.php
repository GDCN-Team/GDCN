<?php

namespace App\Http\Requests\Game\Level\Comment;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class DeleteRequest extends Request
{
    /**
     * @var Comment
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
            $this->comment = Comment::whereId($this->commentID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        $this->auth();
        if (!optional($this->account)->can('delete', $this->comment)) {
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
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'commentID' => [
                'required',
                Rule::exists(Comment::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'levelID' => [
                'required',
                Rule::exists(Level::class, 'id')
            ]
        ];
    }
}
