<?php

namespace App\Exceptions;

use Exception;

class RefreshTokenException extends Exception
{
    public function __construct($message = 'Your token expired, please relink your agency account, if the error persist relink your location.', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

     /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        // for refresh tokens
        if (!$request->expectsJson() && $exception instanceof RefreshTokenException) {
            // info('RefreshTokenException not');
            return view("errors.account_not_configured",["message" => "Your token expired, please relink your agency account."]);
        }
        if ($request->expectsJson() && $exception instanceof RefreshTokenException) {
            // info('RefreshTokenException');
            return response()->json(["message" => "Your token expired, please relink your agency account."], 400);
        }
    }
}