<?php namespace App\Http\Middleware;

use Closure;

class Beta {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			return redirect()->guest('auth/beta');
		}

		return $next($request);
	}

}
