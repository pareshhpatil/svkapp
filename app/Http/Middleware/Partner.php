<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class Partner {

    /**
     * Handle an incoming request.
     *
     * This middleware will validate partner domain or URL and set properties as per partner config
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        #Take request URL from laravel function
        $request_url = $request->getHost();

        #compair with APP_URL from .env if url is diffrent then check partner config file to set partner properties
        if ($request_url != $this->host(env('APP_URL'))) {
            $partners = config('partner');
            $partner_request = false;
            foreach ($partners as $partner) {
                if ($request_url == $this->host($partner['app_url'])) {
                    #If partner url match then change app config details as per partner
                    config(['app.name' => $partner['app_name']]);
                    config(['app.url' => $partner['app_url']]);
                    config(['app.partner' => $partner]);
                    View::share('logo', $partner['logo']);
                    View::share('has_partner', true);
                    $partner_request = true;
                    break;
                }
            }
            #if request url nither matched with base url nor partner url then redirect to 404 page
            if ($partner_request == FALSE) {
                //  return redirect(env('APP_URL') . '/404');
            }
        }

        return $next($request);
    }

    function host($url) {
        if ($url != '') {
            $array = parse_url($url);
            return $array['host'];
        } else {
            return null;
        }
    }

}
