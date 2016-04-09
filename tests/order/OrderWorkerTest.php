<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class OrderWorkerTest extends TestCase {

    use WithoutMiddleware;

    protected $user;
    protected $order;
    protected $maker;
    protected $product;

    public function setUp()
    {
        parent::setUp();

        $this->user = Mockery::mock('App\Droit\User\Repo\UserInterface');
        $this->app->instance('App\Droit\User\Repo\UserInterface', $this->user);

        $this->order = Mockery::mock('App\Droit\Shop\Order\Repo\OrderAdminInterface');
        $this->app->instance('App\Droit\Shop\Order\Repo\OrderAdminInterface', $this->order);

        $this->maker = Mockery::mock('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $this->app->instance('App\Droit\Shop\Order\Worker\OrderMakerInterface', $this->maker);

        $this->product = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product);

        $model = new \App\Droit\User\Entities\User();

        $user = $model->find(710);

        $this->actingAs($user);

    }

    public function tearDown()
    {
        Mockery::close();
        \Cart::instance('newInstance')->destroy();
    }


    public function testNewOrder()
    {

    }

}
