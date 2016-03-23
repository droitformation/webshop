<?php namespace App\Http\Middleware;

use Closure;

class CheckoutMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user    = \Auth::user();
		$adresse = $user->load('adresses')->adresse_livraison;

		if(empty($adresse))
        {
            throw new \App\Exceptions\AdresseNotExistException('No adresse');
		}

        return $next($request);
	}

}
