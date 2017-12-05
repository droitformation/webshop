<?php

namespace App\Http\Middleware;

use Closure;

class AccessUserMiddleware
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
        if(\Auth::user()->access->isEmpty()){
            alert()->warning('Vous n\'avez pas les autorisation pour entrer');
            return redirect('/login');
        }

        return $next($request);
    }
}
