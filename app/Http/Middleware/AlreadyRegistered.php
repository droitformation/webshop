<?php

namespace App\Http\Middleware;

use Closure;
use App\Droit\Inscription\Repo\InscriptionInterface;

class AlreadyRegistered
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
        $exist = $this->inscription->getByUser($request->colloque_id,$request->user_id);

        if($exist)
        {
            alert()->warning('Cet utilisateur est déjà inscrit à ce colloque');
            return redirect()->back()->withInput();
        }

        return $next($request);
    }
}
