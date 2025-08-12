<?php

namespace App\Exceptions;

use App\Traits\ApiResponderTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidationError extends Exception
{
    use ApiResponderTrait;
    public function __invoke(ValidationException $e, Request $request)
    {
        return $this->error($e->getMessage(), 422, $e->errors());
    }
}
