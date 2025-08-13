<?php

namespace App\Exceptions;

use App\Traits\ApiResponderTrait;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GateRequest extends Exception
{
    use ApiResponderTrait;
    public function __invoke(AccessDeniedHttpException $e, Request $request)
    {
        return $this->error($e->getMessage(), 403, null);
    }
}
