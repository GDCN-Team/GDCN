<?php

namespace App\Exceptions\Web\Request;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use JetBrains\PhpStorm\Pure;

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
}
