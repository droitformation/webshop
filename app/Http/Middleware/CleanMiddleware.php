<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\ParameterBag;

class CleanMiddleware
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
        if ($request->isJson()) {
            $this->clean($request->json());
        } else {
            $this->clean($request->request);
        }

        return $next($request);
    }

    private function clean(ParameterBag $bag) {
        $bag->replace($this->cleanData($bag->all()));
    }

    private function cleanData(array $data)
    {
        return collect($data)->map(function ($item, $key) {
            return  is_array($item) ? array_filter($item) : $item;
        })->filter(function($value) {
            return null !== $value;
        })->all();
    }
}
