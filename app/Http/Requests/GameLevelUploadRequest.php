<?php

namespace App\Http\Requests;

use App\Models\GameAccount;
use Illuminate\Validation\Rule;

class GameLevelUploadRequest extends GameRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'gte:21'
            ],
            'binaryVersion' => 'required_with:gameVersion',
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'sometimes',
                'required',
                Rule::exists(GameAccount::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_without:accountID,gjp',
            'uuid' => 'required_with:udid',
            'userName' => 'required',
            'levelID' => 'nullable',
            'levelName' => 'required',
            'levelDesc' => 'nullable',
            'levelVersion' => 'required',
            'levelLength' => [
                'required',
                'between:0,4'
            ],
            'audioTrack' => [
                'required',
                'between:1,21'
            ],
            'auto' => [
                'required',
                'boolean'
            ],
            'password' => 'required',
            'original' => 'required',
            'twoPlayer' => [
                'required',
                'boolean'
            ],
            'songID' => 'required',
            'objects' => 'required',
            'coins' => 'required',
            'requestedStars' => 'required',
            'unlisted' => [
                'required',
                'boolean'
            ],
            'wt' => 'required',
            'wt2' => 'required',
            'ldm' => [
                'required',
                'boolean'
            ],
            'extraString' => 'required',
            'seed' => 'required',
            'seed2' => 'required',
            'levelString' => 'required',
            'levelInfo' => 'required', // anticheat: verify hack
            'secret' => [
                'required',
                Rule::in('Wmfd2893gb7')
            ]
        ];
    }
}
