<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Exceptions\GameRequestAuthorizationException;
use App\Exceptions\GameRequestValidateException;
use App\Exceptions\GameUserNotFoundException;
use App\Game\Helpers;
use App\Models\GameUser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class FriendRequest
 * @package App\Http\Requests\Models
 */
class GameRequest extends FormRequest
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
     * @throws GameUserNotFoundException
     */
    public function getGameUser(bool $autoRegister = true)
    {
        $user = null;
        if (!empty($this->accountID) && !empty($this->gjp)) {
            try {
                $this->auth();

                $account = $this->user();
                $user = $account->user ?? null;
            } catch (GameAuthenticationException $e) {
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
            throw new GameUserNotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @param bool $optional
     * @return bool
     * @throws GameAuthenticationException
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
            throw new GameAuthenticationException('Authentication failed.');
        }

        return false;
    }

    /**
     * @throws GameRequestAuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new GameRequestAuthorizationException('Authorize Failed.');
    }

    /**
     * @param Validator $validator
     * @throws GameRequestValidateException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new GameRequestValidateException($validator);
    }
}
