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

        $roles = $user->roles->pluck('id')->all();

        if(in_array(2,$roles) || in_array(1,$roles))
        {
			return $next($request);
        }

		return redirect('/');
	}

}
