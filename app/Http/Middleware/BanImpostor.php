<?php

namespace App\Http\Middleware;

use Closure;

class BanImpostor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $emails = [
            'bakabienvenu2016@gmail.com',
            'milo.milolo@gmail.com',
            'olombelodi@yahoo.fr',
            'kahidomaliro@gmail.com',
            'kasandemba@gmail.com',
            'mwanzantumba@gmail.com',
        ];

        if(\Auth::check() && in_array(\Auth::user()->email, $emails)){
            abort(404);
        }

        return $next($request);
    }
}
