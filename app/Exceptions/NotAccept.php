<?php

namespace App\Exceptions;

use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class NotAccept extends Exception
{
    use ApiResponder;

    public function __invoke(AccessDeniedHttpException $e, Request $request)
    {
        return $this->error('Unauthorized', 403, null);
    }
}
