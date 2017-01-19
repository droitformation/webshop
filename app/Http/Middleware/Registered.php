<?php

namespace App\Http\Middleware;

use Closure;
use App\Droit\Inscription\Repo\InscriptionInterface;

class Registered
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
        $message = \Registry::get('inscription.messages.registered');

        $pending = \Auth::user()->inscription_pending->mapWithKeys_v2(function ($item, $key) {
            return [$item->colloque_id => $item->rappels->pluck('id')];
        })->filter(function ($value, $key) {
            return !$value->isEmpty();
        });

        if($pending->count() > 1)
        {
            return redirect('colloque')->with(['status' => 'warning', 'message' => $message]);
        }

        return $next($request);
    }
}
