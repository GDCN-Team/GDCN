<?php

namespace App\Http\Requests\Game;

use App\Exceptions\Game\Request\AuthenticationException;
use App\Exceptions\Game\Request\AuthorizationException;
use App\Exceptions\Game\Request\ValidateException;
use App\Exceptions\Game\UserNotFoundException;
use App\Game\Helpers;
use App\Models\GameUser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use function app;

/**
 * Class FriendRequest
 * @package App\Http\Requests\Models
 */
class Request extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * @param bool $autoRegister
     * @return GameUser|Builder|Model
     * @throws UserNotFoundException
     */
    public function getGameUser(bool $autoRegister = true)
    {
        $user = null;
        if (!empty($this->accountID) && !empty($this->gjp)) {
            try {
                $this->auth();

                $account = $this->user();
                $user = $account->user ?? null;
            } catch (AuthenticationException $e) {
                $user = null;
            }
        }

        if (!empty($this->udid) && !$user) {
            try {
                $user = GameUser::whereUuid($this->udid)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                $user = null;
            }
        }

        if ($autoRegister && !empty($this->udid) && !$user) {
            $user = new GameUser();
            $user->name = $this->userName ?? 'Player';
            $user->uuid = !empty($this->accountID) ? $this->accountID : $this->udid ?? null;
            $user->udid = $this->udid ?? null;
            $user->save();
        }

        if (!$user) {
            throw new UserNotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @param bool $optional
     * @return bool
     * @throws AuthenticationException
     */
    public function auth(bool $optional = false): bool
    {
        if (!empty($this->userName) && !empty($this->password)) {
            return Auth::once(['name' => $this->userName, 'password' => $this->password]);
        }

        if (!empty($this->accountID) && !empty($this->gjp)) {
            return Auth::once(['id' => $this->accountID, 'password' => app(Helpers::class)->decodeGJP($this->gjp)]);
        }

        if (!$optional) {
            throw new AuthenticationException('Authentication failed.');
        }

        return false;
    }

    /**
     * @throws AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('Authorize Failed.');
    }

    /**
     * @param Validator $validator
     * @throws ValidateException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidateException($validator);
    }
}
