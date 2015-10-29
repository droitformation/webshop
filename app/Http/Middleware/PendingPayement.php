<?php

namespace App\Http\Middleware;

use Closure;
use App\Droit\Inscription\Repo\InscriptionInterface;

class PendingPayement
{
    protected $inscription;

    /**
     *
     * @return void
     */
    public function __construct(InscriptionInterface $inscription)
    {
        $this->inscription = $inscription;
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
        $message  = \Registry::get('inscription.messages.pending');
        $restrict = \Registry::get('inscription.restrict');

        if($restrict && !$this->inscription->hasPayed(\Auth::user()->id))
        {
            return redirect('colloque')->with(array('status' => 'warning', 'message' => $message ));
        }

        return $next($request);
    }
}
