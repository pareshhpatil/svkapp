<?php

namespace App\Http\Middleware;
use Closure;


class ValidateAccess
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function handle($request,Closure $next)
    {
        return $next($request);

    }
}
