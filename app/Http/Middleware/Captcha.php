<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Http\Request;

class Captcha
{
    /**
     * Handle captcha validations
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Get request JSON data

        if (isset($_POST)) {
            try {
                //your site google secret key
                $secret = env('V3_CAPTCHA_SECRET');
                //get verify response data
                $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret) . '&response=' . urlencode($_POST['recaptcha_response']);
                $response = file_get_contents($url);
                $responseKeys = json_decode($response, true);
                header('Content-type: application/json');
                if ($responseKeys["success"]) {
                    if ($responseKeys["score"] >= 0.05) {
                        return $next($request);
                    }
                }
                return back()->withErrors(['Invalid captcha please try again']);
            } catch (Exception $e) {
                app('sentry')->captureException($e);
            }
        }
    }
}
