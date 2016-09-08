<?php

namespace App\Http\Middleware;

use Closure;

class SiteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd(\Request::segments());

       // echo $prefix = $request->route()->getPrefix();exit;

        return $next($request);
    }
}
