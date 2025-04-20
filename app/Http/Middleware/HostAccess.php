<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class HostAccess
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        $trustedHosts = config('trust-hosts');

        if (! in_array($request->getHost(), $trustedHosts)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
