<?php

namespace App\Http\Requests\Web\Admin\Level;

use App\Models\Game\Level;
use App\Models\Game\Level\Gauntlet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GauntletCreateRequest extends FormRequest
{
    #[ArrayShape(['gauntlet_id' => "\Illuminate\Validation\Rules\Unique", 'level1' => "\Illuminate\Validation\Rules\Exists", 'level2' => "\Illuminate\Validation\Rules\Exists", 'level3' => "\Illuminate\Validation\Rules\Exists", 'level4' => "\Illuminate\Validation\Rules\Exists", 'level5' => "\Illuminate\Validation\Rules\Exists"])] public function rules(): array
    {
        return [
            'gauntlet_id' => Rule::unique(Gauntlet::class),
            'level1' => Rule::exists(Level::class, 'id'),
            'level2' => Rule::exists(Level::class, 'id'),
            'level3' => Rule::exists(Level::class, 'id'),
            'level4' => Rule::exists(Level::class, 'id'),
            'level5' => Rule::exists(Level::class, 'id')
        ];
    }
}
