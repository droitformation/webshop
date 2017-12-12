<?php namespace App\Http\Middleware;

use Closure;

class AdminMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		\Auth::user()->load('roles');
		
        if(!\Auth::user()->roles->contains('id',1) && !\Auth::user()->roles->contains('id',3)) {

            return redirect()->to('/');
        }

		return $next($request);
	}

}
