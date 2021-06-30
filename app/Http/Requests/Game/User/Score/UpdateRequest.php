<?php

namespace App\Http\Requests\Game\User\Score;

use App\Http\Requests\Game\Request;
use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
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
                'sometimes',
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'uuid' => 'required_without_all:accountID,gjp',
            'udid' => 'required_with:uuid',
            'userName' => 'required',
            'stars' => 'required',
            'demons' => 'required',
            'diamonds' => 'required',
            'icon' => 'required',
            'color1' => 'required',
            'color2' => 'required',
            'iconType' => 'required',
            'coins' => 'required',
            'userCoins' => 'required',
            'special' => 'required',
            'secret' => 'required',
            'accIcon' => 'required',
            'accShip' => 'required',
            'accBall' => 'required',
            'accBird' => 'required',
            'accDart' => 'required',
            'accRobot' => 'required',
            'accGlow' => 'required',
            'accSpider' => 'required',
            'accExplosion' => 'required',
            'seed' => 'required',
            'seed2' => 'required'
        ];
    }
}
