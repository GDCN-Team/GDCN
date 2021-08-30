<?php

namespace App\Http\Requests\Game\Level;

use App\Http\Requests\Game\Request;
use App\Models\Game\Level;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class ReportRequest extends Request
{
    #[ArrayShape(['levelID' => "\Illuminate\Validation\Rules\Exists", 'secret' => "\Illuminate\Validation\Rules\In"])] public function rules(): array
    {
        return [
            'levelID' => Rule::exists(Level::class, 'id'),
            'secret' => Rule::in(['Wmfd2893gb7'])
        ];
    }
}
