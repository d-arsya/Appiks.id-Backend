<?php

namespace App\Exceptions;

use App\Traits\ApiResponderTrait;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFound extends Exception
{
    use ApiResponderTrait;
    public function __invoke(NotFoundHttpException $e, Request $request)
    {
        return $this->error("Item not found", 404, null);
    }
}
