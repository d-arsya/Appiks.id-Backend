<?php

namespace App\Exceptions;

use App\Traits\ApiResponderTrait;
use Exception;
use Illuminate\Http\Request;
use PDOException;

class UniqueValueContraint extends Exception
{
    use ApiResponderTrait;
    public function __invoke(PDOException $e, Request $request)
    {
        if ($e->getCode() == "23000") {
            return $this->error("Unique value constraint", 400, $e);
        }
    }
}
