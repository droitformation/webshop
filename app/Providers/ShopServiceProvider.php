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
        $this->registerPaymentService();
        $this->registerCartService();
        $this->registerCategorieService();

        $this->registerOrderWorkerService();
        $this->registerOrderAdminWorkerService();
        $this->registerCartWorkerService();
        $this->registerPdfGeneratorService();

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
     * Categorie
     */
    protected function registerCategorieService(){

        $this->app->singleton('App\Droit\Shop\Categorie\Repo\CategorieInterface', function()
        {
            return new \App\Droit\Shop\Categorie\Repo\CategorieEloquent(
                new \App\Droit\Shop\Categorie\Entities\Categorie
            );
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

    /**
     * Payment
     */
    protected function registerPaymentService(){

        $this->app->singleton('App\Droit\Shop\Payment\Repo\PaymentInterface', function()
        {
            return new \App\Droit\Shop\Payment\Repo\PaymentEloquent(new \App\Droit\Shop\Payment\Entities\Payment);
        });
    }

    /**
     * Cart
     */
    protected function registerCartService(){

        $this->app->singleton('App\Droit\Shop\Cart\Repo\CartInterface', function()
        {
            return new \App\Droit\Shop\Cart\Repo\CartEloquent(new \App\Droit\Shop\Cart\Entities\Cart);
        });
    }

    /**
     * OrderWorker
     */
    protected function registerOrderWorkerService(){

        $this->app->singleton('App\Droit\Shop\Order\Worker\OrderWorkerInterface', function()
        {
            return new \App\Droit\Shop\Order\Worker\OrderWorker(
                \App::make('App\Droit\Shop\Order\Repo\OrderInterface'),
                \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface'),
                \App::make('App\Droit\User\Repo\UserInterface'),
                \App::make('App\Droit\Shop\Cart\Repo\CartInterface'),
                \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface')
            );
        });
    }

    /**
     * OrderWorker for admin
     */
    protected function registerOrderAdminWorkerService(){

        $this->app->singleton('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface', function()
        {
            return new \App\Droit\Shop\Order\Worker\OrderAdminWorker(
                \App::make('App\Droit\Shop\Order\Repo\OrderInterface'),
                \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface'),
                \App::make('App\Droit\User\Repo\UserInterface'),
                \App::make('App\Droit\Shop\Cart\Repo\CartInterface'),
                \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface'),
                \App::make('App\Droit\Shop\Product\Repo\ProductInterface'),
                \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface'),
                \App::make('App\Droit\Adresse\Repo\AdresseInterface')
            );
        });
    }

    /**
     * CartWorker
     */
    protected function registerCartWorkerService(){

        $this->app->singleton('App\Droit\Shop\Cart\Worker\CartWorkerInterface', function()
        {
            return new \App\Droit\Shop\Cart\Worker\CartWorker(
                \App::make('App\Droit\Shop\Product\Repo\ProductInterface'),
                \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface'),
                \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface')
            );
        });
    }

    /**
     * PdfGenerator
     */
    protected function registerPdfGeneratorService(){

        $this->app->singleton('App\Droit\Generate\Pdf\PdfGeneratorInterface', function()
        {
            return new \App\Droit\Generate\Pdf\PdfGenerator(
                \App::make('App\Droit\Shop\Order\Repo\OrderInterface'),
                \App::make('App\Droit\User\Repo\UserInterface')
            );
        });
    }

}
