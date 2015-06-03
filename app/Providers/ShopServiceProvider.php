<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->registerProductService();
        $this->registerAttributeService();
	}

    /**
     * Product
     */
    protected function registerProductService(){

        $this->app->bindShared('App\Droit\Shop\Product\Repo\ProductInterface', function()
        {
            return new \App\Droit\Shop\Product\Repo\ProductEloquent(new \App\Droit\Shop\Product\Entities\Product);
        });
    }

    /**
     * Attribute
     */
    protected function registerAttributeService(){

        $this->app->bindShared('App\Droit\Shop\Attribute\Repo\AttributeInterface', function()
        {
            return new \App\Droit\Shop\Attribute\Repo\AttributeEloquent(new \App\Droit\Shop\Attribute\Entities\Attribute);
        });
    }

}
