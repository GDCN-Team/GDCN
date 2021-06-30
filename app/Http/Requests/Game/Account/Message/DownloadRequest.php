<?php

namespace App\Http\Requests\Game\Account\Message;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use App\Models\GameAccountMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class DownloadRequest extends Request
{
    /**
     * @var GameAccountMessage
     */
    public $message;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->messageID)) {
            return false;
        }

        try {
            $this->auth();
            $this->message = GameAccountMessage::whereId($this->messageID)->firstOrFail();
        } catch (AuthenticationException | ModelNotFoundException $e) {
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
            'messageID' => [
                'required',
                Rule::exists(GameAccountMessage::class, 'id')
            ],
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'isSender' => [
                'sometimes',
                'required',
                'boolean'
            ]
        ];
    }
}
