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
        $this->registerShippingService();
        $this->registerProductService();
        $this->registerOrderService();
        $this->registerAttributeService();
        $this->registerCouponService();

	}


    /**
     * Product
     */
    protected function registerProductService(){

        $this->app->singleton('App\Droit\Shop\Product\Repo\ProductInterface', function()
        {
            return new \App\Droit\Shop\Product\Repo\ProductEloquent(new \App\Droit\Shop\Product\Entities\Product);
        });
    }

    /**
     * Order
     */
    protected function registerOrderService(){

        $this->app->singleton('App\Droit\Shop\Order\Repo\OrderInterface', function()
        {
            return new \App\Droit\Shop\Order\Repo\OrderEloquent(new \App\Droit\Shop\Order\Entities\Order);
        });
    }

    /**
     * Shipping
     */
    protected function registerShippingService(){

        $this->app->singleton('App\Droit\Shop\Shipping\Repo\ShippingInterface', function()
        {
            return new \App\Droit\Shop\Shipping\Repo\ShippingEloquent(new \App\Droit\Shop\Shipping\Entities\Shipping);
        });
    }


    /**
     * Attribute
     */
    protected function registerAttributeService(){

        $this->app->singleton('App\Droit\Shop\Attribute\Repo\AttributeInterface', function()
        {
            return new \App\Droit\Shop\Attribute\Repo\AttributeEloquent(new \App\Droit\Shop\Attribute\Entities\Attribute);
        });
    }


    /**
     * Coupon
     */
    protected function registerCouponService(){

        $this->app->singleton('App\Droit\Shop\Coupon\Repo\CouponInterface', function()
        {
            return new \App\Droit\Shop\Coupon\Repo\CouponEloquent(new \App\Droit\Shop\Coupon\Entities\Coupon);
        });
    }

}
