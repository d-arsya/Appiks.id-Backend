<?php

namespace App\Exceptions;

use App\Traits\ApiResponderTrait;
use Exception;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Http\Request;

class NotFound extends Exception
{
    use ApiResponderTrait;
    public function __invoke(NotFoundException $e, Request $request)
    {
        return $this->error("Item not found", 404, null);
    }
}
