<?php

namespace App\Http\Requests;

use App\Exceptions\GameAuthenticationException;
use App\Exceptions\GameRequestAuthorizationException;
use App\Exceptions\GameRequestValidateException;
use App\Exceptions\GameUserNotFoundException;
use App\Exceptions\RequestCheckException;
use App\Game\Helpers;
use App\Models\GameUser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
     * @param bool $ignoreException
     * @return bool
     * @throws GameAuthenticationException
     */
    public function auth(bool $ignoreException = false): bool
    {
        if (!empty($this->userName) && !empty($this->password)) {
            return Auth::once(['name' => $this->userName, 'password' => $this->password]);
        }

        if (!empty($this->accountID) && !empty($this->gjp)) {
            return Auth::once(['id' => $this->accountID, 'password' => app(Helpers::class)->decodeGJP($this->gjp)]);
        }

        if (!$ignoreException) {
            throw new GameAuthenticationException('Authentication failed.');
        }

        return false;
    }

    /**
     * @param array $data
     * @return array
     * @throws RequestCheckException
     */
    public function check(array $data): array
    {
        foreach ($data as $key => $value) {
            $str = Str::of($value);
            $isOptionValue = $str->startsWith('?');
            if ($isOptionValue) {
                $value = substr($value, 1);
            } else {
                $checkValues[] = is_numeric($key) ? $value : $key;
            }

            if (!is_numeric($key)) {
                $equals[$key] = $value;
                $values[] = $key;
            } else {
                $values[] = $value;
            }
        }

        $data = $this->only($values ?? []);
        if (!$this->has($checkValues ?? [])) {
            throw new RequestCheckException('Missing Parameters');
        }

        foreach ($equals ?? [] as $key => $value) {
            if (empty($data[$key]) || $data[$key] !== $value) {
                throw new RequestCheckException($data[$key] . ' Not Equal As ' . $value);
            }
        }

        return $data;
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
        throw new GameRequestValidateException($validator->errors()->first());
    }
}
