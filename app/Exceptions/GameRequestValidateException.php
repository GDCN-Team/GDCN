<?php

namespace App\Exceptions;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class GameRequestValidateException
 * @package App\Exceptions
 */
class GameRequestValidateException extends Exception
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * GameRequestValidateException constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        parent::__construct('Request Validate Failed!');
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
