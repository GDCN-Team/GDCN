<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Models\GameAccount;
use App\Models\GameAccountMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class GameAccountMessageDeleteRequest extends GameRequest
{
    /**
     * @var Collection
     */
    public $messages;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            $this->auth();
            if (!empty($this['messages'])) {
                $messageIds = explode(',', $this['messages']);
            } else if (!empty($this->messageID)) {
                $messageIds = Arr::wrap($this->messageID);
            } else {
                return false;
            }

            $this->messages = GameAccountMessage::query()
                ->findMany($messageIds);

        } catch (GameAuthenticationException $e) {
            return false;
        }

        return count($this->messages) > 0;
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
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ],
            'isSender' => [
                'sometimes',
                'required',
                'boolean'
            ],
            'messageID' => [ // single delete
                'sometimes',
                'required',
                Rule::exists(GameAccountMessage::class, 'id')
            ],
            'messages' => 'required_without:messageID' // multi delete
        ];
    }
}
