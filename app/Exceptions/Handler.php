<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            // Get and delete redirect from session
            return response()->json(['code'=> 403, 'message' => $exception->getMessage()], 403);
        }
        if ($this->isHttpException($exception)) {
            return response()->json(['code'=> $exception->getStatusCode(), 'message' => $exception->getMessage()], $exception->getStatusCode());
        }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception) {
        return $request->expectsJson()
                    ? response()->json(['code'=>401,'message' => $exception->getMessage()], 401)
                    : redirect()->guest(route('login'));
    }
}
