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
        $user = \Auth::user();
        $user->load('roles');

        if(!$user->roles->contains('id',1)) {

			if($user->roles->contains('id',2)){
				return redirect()->to('/team');
			}
			
            return redirect()->to('/');
        }

		return $next($request);
	}

}
