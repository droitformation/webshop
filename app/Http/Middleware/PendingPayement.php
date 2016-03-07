<?php

namespace App\Http\Middleware;

use Closure;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

class PendingPayement
{
    protected $inscription;
    protected $order;

    /**
     *
     * @return void
     */
    public function __construct(InscriptionInterface $inscription, OrderInterface $order)
    {
        $this->inscription = $inscription;
        $this->order = $order;
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
        $message           = \Registry::get('inscription.messages.pending');
        $restrict_shop     = \Registry::get('shop.restrict');

        if ($request->is('checkout/*'))
        {
            if( $restrict_shop && !$this->order->hasPayed(\Auth::user()->id) )
            {
                return redirect('shop')->with(array('status' => 'warning', 'message' => $message ));
            }
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $restrict_colloque = \Registry::get('inscription.restrict');

        if (\Auth::check() && $request->is('colloque/*'))
        {
            if($restrict_colloque && !$this->inscription->hasPayed(\Auth::user()->id))
            {
                session(['pending' => 'value']);
            }
        }

    }
}
