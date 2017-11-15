<?php

namespace App\Http\Middleware;

use Closure;

class HoneyBot
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
        if($request->isMethod('POST') && count($request->input('honey_pot')) != 0 ){

            return redirect('/404');
        }
        return $next($request);
    }
}
