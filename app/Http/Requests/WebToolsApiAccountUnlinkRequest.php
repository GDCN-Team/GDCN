<?php

namespace App\Http\Requests;

use App\Models\GameAccountLink;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WebToolsApiAccountUnlinkRequest extends ApiRequest
{
    /**
     * @var GameAccountLink
     */
    public $link;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->linkID)) {
            return false;
        }

        try {
            $this->link = GameAccountLink::findOrFail($this->linkID);
            $accountID = Auth::id();
            if (!$this->link->account === $accountID) {
                return false;
            }
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
            'linkID' => [
                'required',
                Rule::exists(GameAccountLink::class, 'id')
            ]
        ];
    }
}
