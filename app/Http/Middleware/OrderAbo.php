<?php

namespace App\Http\Middleware;

use Closure;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;

class OrderAbo
{
    protected $abonnement;
    protected $worker;

    /**
     *
     * @return void
     */
    public function __construct(AboUserInterface $abonnement, CartWorkerInterface $worker)
    {
        $this->abonnement = $abonnement;
        $this->worker     = $worker;
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
        // get abos from request or in cart already
        $abos = $this->getAbos($request);

        // if user is authenticated
        if(\Auth::check())
        {
            $user = \Auth::user()->load('adresses');

            // No adresse , no abo yet
            if(!isset($user->adresse_livraison)) {

                $request->session()->flash('AdresseMissing', 'Error');

                return redirect()->back();
            }

            foreach($abos as $abo)
            {
                $exist = $this->abonnement->findByAdresse($user->adresse_livraison->id, $abo);

                if($exist)
                {
                    $this->worker->removeById('abonnement', $abo);
                    $redirect = true;
                }
            }
        }

        if(isset($redirect))
        {
            $request->session()->flash('OrderAbo', 'Error');

            return redirect('pubdroit');
        }

        return $next($request);
    }

    protected function getAbos($request)
    {
        $abo_id = $request->input('abo_id',null);

        if(!\Cart::instance('abonnement')->content()->isEmpty())
        {
            $abos = \Cart::instance('abonnement')->content()->map(function ($item, $key) {
                return $item->id;
            });
        }

        return isset($abos) ? $abos : [$abo_id];
    }
}
