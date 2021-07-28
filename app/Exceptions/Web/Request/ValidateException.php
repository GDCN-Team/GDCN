<?php

namespace App\Exceptions\Web\Request;

use App\Http\Controllers\Web\Traits\ResponseTrait;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Request;

class ValidateException extends Exception
{
    use ResponseTrait;

    /**
     * ValidateException constructor.
     * @param Validator $validator
     */
    public function __construct(
        protected Validator $validator
    )
    {
        parent::__construct();
    }

    public function render(): array
    {
        if (Request::isXmlHttpRequest()) {
            $firstError = $this->validator->errors()->first();
            return $this->response(false, $firstError);
        }

        abort(422);
    }
}
