<?php

namespace App\Http\Requests\Web\NGProxy;

use Illuminate\Foundation\Http\FormRequest;

class SongInfoGetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'songID' => 'integer|min:1'
        ];
    }
}
