<?php

namespace App\Http\Requests\Game;

use App\Exceptions\Game\Request\AuthorizationException;
use App\Exceptions\Game\Request\ValidateException;
use App\Game\Helpers;
use App\Models\Game\Account;
use App\Models\Game\User;
use Illuminate\Contracts\Validation\Validator;
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
     * @var Account|null
     */
    public $account;

    /**
     * @var User|null
     */
    public $user;

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
     * @return bool
     */
    public function validateAccount(): bool
    {
        if (!$this->has(['userName', 'password'])) {
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
        if (!$this->has(['accountID', 'gjp'])) {
            return false;
        }

        return Auth::once([
            'id' => $this->get('accountID'),
            'password' => app(Helpers::class)->decodeGJP($this->get('gjp'))
        ]);
    }

    /**
     * @return bool
     */
    public function validateUser(): bool
    {
        if (!$this->has(['uuid', 'udid'])) {
            return false;
        }

        $auth = Auth::guard('game.user');
        $attempt = $auth->once([
            'id' => $this->get('uuid'),
            'udid' => $this->get('udid')
        ]);

        if (!$attempt) {
            $user = new User();
            $udid = $this->get('udid');
            $user->uuid = $udid;
            $user->udid = $udid;
            $user->name = $this->get('name');
            $user->save();

            $attempt = $auth->once($user->toArray());
        }

        return $attempt;
    }

    /**
     * @return bool
     */
    public function auth(): bool
    {
        return true;
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
        throw new ValidateException($validator);
    }
}
