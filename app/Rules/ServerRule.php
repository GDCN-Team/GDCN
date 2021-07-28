<?php

namespace App\Rules;

use App\Http\Controllers\Web\Traits\ServerTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class ServerRule implements Rule
{
    use ServerTrait;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Arr::has($this->servers, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return ':attribute 不是一个有效的服务器名称';
    }
}
