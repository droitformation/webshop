<?php

namespace App\Http\Middleware;

use Closure;

class AccountUpdateMiddleware
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
        if($request->input('id') != \Auth::user()->id){
            return abort('404');
        }

        return $next($request);
    }
}
