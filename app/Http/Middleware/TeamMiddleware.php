<?php namespace App\Http\Middleware;

use Closure;

class TeamMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $user = \Auth::user();
        $user->load('roles');

		if($user->roles->contains('id',1) || $user->roles->contains('id',2)){
			return $next($request);
        }

		return redirect('/');
	}
}
