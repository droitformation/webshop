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
        if($request->input('type') == 'simple')
        {
            $exist = $this->inscription->isRegistered($request->input('colloque_id'),$request->input('user_id'));

            if($exist)
            {
                flash('Cet utilisateur est déjà inscrit à ce colloque')->warning();
                return redirect()->back()->withInput();
            }
        }

        return $next($request);
    }
}
