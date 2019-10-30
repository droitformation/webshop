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

        collect()->macro('mapWithKeys_v2', function ($callback) {

            $result = [];

            foreach ($this->items as $key => $value) {
                $assoc = $callback($value, $key);

                foreach ($assoc as $mapKey => $mapValue) {
                    $result[$mapKey] = $mapValue;
                }
            }

            return new static($result);
        });

        collect()->macro('dilate', function () {

            $result = [];

            foreach ($this->items as $i => $value) {
                $result[$i][] = $value;
            }

            return new static($result);
        });

        Collection::macro('transpose', function () {
            $items = array_map(function (...$items) {
                return $items;
            }, ...$this->values());

            return new static($items);
        });

        Collection::macro('dateBiggerThan', function ($column,$date) {

            return collect($this->items)->filter(function ($value, $key) use ($column,$date) {
                return $value->$column < $date;
            });

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
