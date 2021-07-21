<?php

namespace App\Exceptions\Game\Request;

use App\Enums\Game\ResponseCode;
use Exception;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class ValidateException
 * @package App\Exceptions
 */
class ValidateException extends Exception
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * ValidateException constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
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
