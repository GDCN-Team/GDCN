<?php

namespace App\Http\Requests\Game\Level\Rating;

use App\Http\Requests\Game\Request;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Rules\ValidateAccountCreditRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SuggestStarsRequest extends Request
{
    #[ArrayShape(['gameVersion' => "string", 'binaryVersion' => "string", 'gdw' => "string", 'accountID' => "\Illuminate\Validation\Rules\Exists", 'gjp' => "\App\Rules\ValidateAccountCreditRule", 'levelID' => "\Illuminate\Validation\Rules\Exists", 'stars' => "string", 'feature' => "string", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'gameVersion' => 'required',
            'binaryVersion' => 'required',
            'gdw' => 'required',
            'accountID' => Rule::exists(Account::class, 'id'),
            'gjp' => new ValidateAccountCreditRule(),
            'levelID' => Rule::exists(Level::class, 'id'),
            'stars' => 'between:1,10',
            'feature' => 'boolean',
            'secret' => Rule::in(['Wmfp3879gc3'])
        ];
    }
}
