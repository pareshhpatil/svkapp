<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class Subdomain
{

    /**
     * Handle an incoming request.
     *
     * This middleware will validate partner domain or URL and set properties as per partner config
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        #Take request URL from laravel function
        $request_url = $request->getHost();
        #compair with APP_URL from .env if url is different than check partner config file to set partner properties
        if ($request_url != $this->host(env('APP_URL'))) {
            $subdomain = current(explode('.', $request->getHost()));
            $exclude = ['api', 'swipez','www'];
            if (!in_array($subdomain, $exclude)) {
                $exist = DB::table('merchant')->where('display_url', $subdomain)->first();
                if ($exist != null) {
                    config(['app.merchant_subdomain' => $subdomain]);
                }
            }
        }

        return $next($request);
    }

    function host($url)
    {
        if ($url != '') {
            $array = parse_url($url);
            return $array['host'];
        } else {
            return null;
        }
    }
}
