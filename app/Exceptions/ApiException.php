<?php

namespace App\Exceptions;
use Throwable;

class ApiException extends \Exception
{

    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        if(app()->environment() !== 'local')
            $message = 'Sorry we couldn\'t handle your request, Please contact support';

        parent::__construct($message, $code, $previous);
    }
}
