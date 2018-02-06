<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
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
        $this->registerOrderService();
        $this->registerRappelService();
        $this->registerAttributeService();
        $this->registerCategorieService();
        $this->registerAuthorService();

        $this->registerCouponService();
        $this->registerShippingService();
        $this->registerPaquetService();
        $this->registerPaymentService();
        $this->registerCartService();
        $this->registerStockService();

        $this->registerOrderMakerService();
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
            return new \App\Droit\Shop\Order\Repo\OrderEloquent(
                new \App\Droit\Shop\Order\Entities\Order(),
                new \App\Droit\Shop\Shipping\Entities\Paquet()
            );
        });
    }

    /**
     * Order
     */
    protected function registerRappelService(){

        $this->app->singleton('App\Droit\Shop\Rappel\Repo\RappelInterface', function()
        {
            return new \App\Droit\Shop\Rappel\Repo\RappelEloquent(new \App\Droit\Shop\Rappel\Entities\Rappel);
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
     * Paquet
     */
    protected function registerPaquetService(){

        $this->app->singleton('App\Droit\Shop\Shipping\Repo\PaquetInterface', function()
        {
            return new \App\Droit\Shop\Shipping\Repo\PaquetEloquent(new \App\Droit\Shop\Shipping\Entities\Paquet);
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
     * Author
     */
    protected function registerAuthorService(){

        $this->app->singleton('App\Droit\Shop\Author\Repo\AuthorInterface', function()
        {
            return new \App\Droit\Shop\Author\Repo\AuthorEloquent(new \App\Droit\Shop\Author\Entities\Author);
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
     * Stock
     */
    protected function registerStockService(){

        $this->app->singleton('App\Droit\Shop\Stock\Repo\StockInterface', function()
        {
            return new \App\Droit\Shop\Stock\Repo\StockEloquent(new \App\Droit\Shop\Stock\Entities\Stock);
        });
    }

    /**
     * OrderMaker
     */
    protected function registerOrderMakerService(){

        $this->app->singleton('App\Droit\Shop\Order\Worker\OrderMakerInterface', function()
        {
            return new \App\Droit\Shop\Order\Worker\OrderMaker(
                \App::make('App\Droit\Shop\Order\Repo\OrderInterface'),
                \App::make('App\Droit\Shop\Product\Repo\ProductInterface'),
                \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface'),
                \App::make('App\Droit\Adresse\Repo\AdresseInterface'),
                \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface'),
                \App::make('App\Droit\Shop\Cart\Repo\CartInterface'),
                \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface'),
                \App::make('App\Droit\Shop\Stock\Repo\StockInterface')
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
