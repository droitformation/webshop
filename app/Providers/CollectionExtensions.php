<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class CollectionExtensions extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('flattenWithKey', function () {

            $items = $this->reduce(function ($list, $items)
            {
                return $items->toArray() + $list;
            }, []);

            return new \Illuminate\Support\Collection($items);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
