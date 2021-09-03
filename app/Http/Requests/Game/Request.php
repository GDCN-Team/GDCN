<?php

namespace App\Http\Requests\Game;

use App\Exceptions\Game\Request\AuthorizationException;
use App\Exceptions\Game\Request\ValidateException;
use App\Models\Game\Account;
use App\Models\Game\User;
use GDCN\Hash\Components\GJP as GJPComponent;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class FriendRequest
 * @package App\Http\Requests\Models
 */
class Request extends FormRequest
{
    /**
     * @inerhitDoc
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * @return false
     */
    public function validateAccount(): bool
    {
        if (!$this->filled(['userName', 'password'])) {
            return false;
        }

        return Auth::once([
            'name' => $this->get('userName'),
            'password' => $this->get('password')
        ]);
    }

    /**
     * @return bool
     */
    public function validateAccountGJP(): bool
    {
        if (!$this->filled(['accountID', 'gjp'])) {
            return false;
        }

        return Auth::once([
            'id' => $this->get('accountID'),
            'password' => app(GJPComponent::class)->decode($this->get('gjp'))
        ]);
    }

    /**
     * @return User|null
     */
    public function getPlayer(): ?User
    {
        if (
            $this->filled(['accountID', 'gjp']) && $this->validateAccountGJP() ||
            $this->filled(['userName', 'password']) && $this->validateAccount()
        ) {
            /** @var Account $account */
            $account = Auth::user();

            return $account->user;
        }

        if ($this->filled(['uuid', 'udid'])) {
            $user = User::where([
                'id' => $this->get('uuid')
            ])->orWhere([
                'uuid' => $this->get('udid'),
                'udid' => $this->get('udid')
            ])->first();

            if (!$user) {
                $user = new User();
                $user->name = $this->get('userName', 'Player');
                $user->uuid = $this->get('udid');
                $user->udid = $this->get('udid');
                if ($user->save()) {
                    return $user;
                }
            } else {
                return $user;
            }
        }

        return null;
    }

    /**
     * @throws AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new AuthorizationException();
    }

    /**
     * @param Validator $validator
     * @throws ValidateException
     */
    protected function failedValidation(Validator $validator): void
    {
        Log::channel('gdcn')
            ->info('[Game Request System] Request Validate Failed', [
                'errors' => $validator->errors()
            ]);

        throw new ValidateException($validator);
    }
}
