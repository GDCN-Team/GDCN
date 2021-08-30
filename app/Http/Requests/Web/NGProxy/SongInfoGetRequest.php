<?php

namespace App\Http\Requests\Web\NGProxy;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SongInfoGetRequest extends FormRequest
{
    #[ArrayShape(['songID' => "string"])] public function rules(): array
    {
        return [
            'songID' => 'integer|min:1'
        ];
    }
}
