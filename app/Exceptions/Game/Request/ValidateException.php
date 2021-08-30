<?php

namespace App\Exceptions\Game\Request;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use JetBrains\PhpStorm\Pure;

/**
 * Class ValidateException
 * @package App\Exceptions
 */
class ValidateException extends Exception
{

    /**
     * ValidateException constructor.
     * @param Validator $validator
     */
    #[Pure] public function __construct(
        protected Validator $validator
    )
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function render(): int
    {
        $message = $this->validator->errors()->first();
        return is_numeric($message) ? $message : ResponseCode::REQUEST_CHECK_FAILED;
    }
}
