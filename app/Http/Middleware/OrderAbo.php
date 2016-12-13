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
        $redirect = false;

        $abo_id = $request->input('abo_id',null);

        if($abo_id)
        {
            $abos = [$abo_id];
        }

        if(empty($abos) && !\Cart::instance('abonnement')->content()->isEmpty())
        {
            $abos = \Cart::instance('abonnement')->content()->map(function ($item, $key) {
                return $item->id;
            });
        }

        if(\Auth::check() && !empty($abos))
        {
            $user = \Auth::user()->load('adresses');

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

        if($redirect)
        {
            session(['OrderAbo' => 'Error']);
            $request->session()->flash('OrderAbo', 'Error');
            
            return redirect()->back();
        }

        return $next($request);
    }
}
