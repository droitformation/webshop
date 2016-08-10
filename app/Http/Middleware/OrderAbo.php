<?php

namespace App\Http\Middleware;

use Closure;
use App\Droit\Abo\Repo\AboUserInterface;

class OrderAbo
{
    protected $abonnement;

    /**
     *
     * @return void
     */
    public function __construct(AboUserInterface $abonnement)
    {
        $this->abonnement = $abonnement;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check())
        {
            $user = \Auth::user()->load('adresses');
            $exist = $this->abonnement->findByAdresse($user->adresse_livraison->id, $request->input('abo_id'));

            if($exist)
            {
                \Cart::instance('abonnement')->destroy();
                return redirect('/')->with(['status' => 'warning', 'message' => 'Vous êtes déjà abonné à cet ouvrage']);
            }

            return $next($request);
        }

        return $next($request);

    }
}
