<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use Illuminate\Validation\Rule;

class UploadRequest extends Request
{
    /**
     * @inerhitDoc
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->filled(['accountID', 'gjp'])) {
            return $this->validateAccountGJP();
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
                'sometimes',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => 'required_with:accountID',
            'udid' => 'required_without:accountID,gjp',
            'uuid' => 'required_with:udid',
            'userName' => 'required',
            'levelID' => 'nullable',
            'levelName' => 'required',
            'levelDesc' => 'nullable',
            'levelVersion' => 'required',
            'levelLength' => 'between:0,4',
            'audioTrack' => 'between:0,21',
            'auto' => 'boolean',
            'password' => 'required',
            'original' => 'required',
            'twoPlayer' => 'boolean',
            'songID' => 'required',
            'objects' => 'required',
            'coins' => 'required',
            'requestedStars' => 'required',
            'unlisted' => 'boolean',
            'wt' => 'required',
            'wt2' => 'required',
            'ldm' => 'boolean',
            'extraString' => 'required',
            'seed' => 'required',
            'seed2' => 'required',
            'levelString' => 'required',
            'levelInfo' => 'nullable',
            'secret' => Rule::in('Wmfd2893gb7')
        ];
    }
}
