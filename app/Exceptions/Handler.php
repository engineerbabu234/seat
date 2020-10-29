<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Redirect;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception){
        if ($exception instanceof MethodNotAllowedHttpException) {
           return response(['status' => false , 'message' => 'Method is not allowed for the requested route' ],405); 
        }

         if ($exception instanceof TokenMismatchException) {
            return back()->with('error','Your page is expired,please again login');
         } 
        //return response()->view('404', [], 404);

        if ($exception instanceof NotFoundHttpException) {
            if ($request->is('api/*')) {
                return response(['status' => false , 'message' => 'Page not Found' ],404);
            }
            return response()->view('404', [], 404);
        }
        return parent::render($request, $exception);
    }
}
