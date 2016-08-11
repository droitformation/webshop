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
        //$this->aboExist($request->input('abo_id',null));

        return $next($request);
    }

    public function aboExist($abo_id)
    {
        if($abo_id)
        {
            $abos = [$abo_id];
        }

        if(!isset($abo_id) && !\Cart::instance('abonnement')->isEmpty())
        {
            $abos = \Cart::instance('abonnement')->content()->pluck('id');
        }

        if (\Auth::check() && !empty($abos))
        {
            foreach($abos as $abo)
            {
                $user = \Auth::user()->load('adresses');
                $exist = $this->abonnement->findByAdresse($user->adresse_livraison->id, $abo);

                if($exist)
                {
                    $toRemove = \Cart::instance('abonnement')->search(function ($cartItem, $rowId) use ($abo) {
                        return $cartItem->id === $abo;
                    });

                    $ids = $toRemove->pluck('rowId');

                    return redirect('/')->with(['status' => 'warning', 'message' => 'Vous êtes déjà abonné à cet ouvrage']);
                }
            }
        }
    }
}
