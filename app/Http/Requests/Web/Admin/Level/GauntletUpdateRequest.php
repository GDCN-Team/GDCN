<?php

namespace App\Http\Requests\Web\Admin\Level;

use App\Models\Game\Level;
use App\Models\Game\Level\Gauntlet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GauntletUpdateRequest extends FormRequest
{
    #[ArrayShape(['type' => "\Illuminate\Validation\Rules\Unique", 'level1' => "\Illuminate\Validation\Rules\Exists", 'level2' => "\Illuminate\Validation\Rules\Exists", 'level3' => "\Illuminate\Validation\Rules\Exists", 'level4' => "\Illuminate\Validation\Rules\Exists", 'level5' => "\Illuminate\Validation\Rules\Exists"])] public function rules(): array
    {
        return [
            'type' => Rule::unique(Gauntlet::class, 'gauntlet_id')->ignore(Request::route('gauntlet')),
            'level1' => Rule::exists(Level::class, 'id'),
            'level2' => Rule::exists(Level::class, 'id'),
            'level3' => Rule::exists(Level::class, 'id'),
            'level4' => Rule::exists(Level::class, 'id'),
            'level5' => Rule::exists(Level::class, 'id')
        ];
    }
}
