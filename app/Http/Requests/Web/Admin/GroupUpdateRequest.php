<?php

namespace App\Http\Requests\Web\Admin;

use App\Models\Game\Account\Permission\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class GroupUpdateRequest extends FormRequest
{
    #[ArrayShape(['name' => "\Illuminate\Validation\Rules\Unique", 'mod_level' => "\Illuminate\Validation\Rules\In", 'comment_color' => "string"])] public function rules(): array
    {
        return [
            'name' => Rule::unique(Group::class)->ignore(Request::route('group')),
            'mod_level' => Rule::in([0, 1, 2]),
            'comment_color' => 'required'
        ];
    }
}
