<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class CustomException extends Exception
{
    public function __construct($message = '', $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  Request $request
     * @return Response
     */
    public function render($request)
    {
        return response(['status' => false, 'message' => $this->getMessage()], $this->getCode());
    }
}
