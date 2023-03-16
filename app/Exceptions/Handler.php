<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\App as App;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\API\APIController;

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

    private $apiController = null;

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {

        if ($this->shouldReport($exception) && app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            // pass to legacy framework - contents of index.php
            App::make("SwipezLegacyFramework");
            die();
        }

        if ($exception instanceof AuthorizationException) {
            // redirect to no-permission page
            return redirect('/merchant/no-permission');
        }

        //this is only for apis request to handle unatheticated exception
        $is_api_request = $request->route()->getPrefix() === 'api';
        if ($is_api_request) {
            $this->apiController = new APIController();
            //define('REQ_TIME', date("Y-m-d H:i:s"));
            if ($exception instanceof AuthenticationException) {
                return response()->json($this->apiController->APIResponse('ER02056'), 401);
            } else {
                return response()->json($this->apiController->APIResponse('ER02057'), 401);
            }
        }

        $response = parent::render($request, $exception);
        if (env('APP_ENV') == 'LOCAL' || env('APP_ENV') == 'DEV') {
            dd($exception);
            return $response;
        }

        return response(view('errors.system', ['status' =>  $response->status()]), $response->status());
    }
}
