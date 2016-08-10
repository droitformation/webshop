<?php namespace App\Http\Middleware;

use Closure;

class CartMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if (\Cart::instance('shop')->content()->isEmpty() && \Cart::instance('abonnement')->content()->isEmpty())
        {
            return redirect('/');
        }

        return $next($request);
	}

}
