<?php

class OrderWorkerTest extends TestCase {

    protected $mock;
    protected $user;
    protected $order;
    protected $cart;
    protected $product;

    public function setUp()
    {
        parent::setUp();

        $this->user = Mockery::mock('App\Droit\User\Repo\UserInterface');
        $this->app->instance('App\Droit\User\Repo\UserInterface', $this->user);

        $this->order = Mockery::mock('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->app->instance('App\Droit\Shop\Order\Repo\OrderInterface', $this->order);

        $this->cart = Mockery::mock('App\Droit\Shop\Cart\Repo\CartInterface');
        $this->app->instance('App\Droit\Shop\Cart\Repo\CartInterface', $this->cart);

        $this->product = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product);

        $user = App\Droit\User\Entities\User::find(1);
        $this->be($user);
    }

    public function tearDown()
    {
        Mockery::close();
        \Cart::instance('newInstance')->destroy();
    }

    /**
     * @return void
     */
    public function testGetProductIdFromCart()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderWorkerInterface');

        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));
        \Cart::add(57, 'tres', 1, '35' , array('weight' => 125));

        $result = $worker->productIdFromCart();

        $this->assertEquals([55,55,56,57], $result);
    }

    /**
     * @return void
     */
    public function testMakeNewOrder()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderWorkerInterface');

        // Price 2.00 chf
        \Cart::add(55, 'Uno', 1, '100' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '100' , array('weight' => 25));

        //$coupon   = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();
        $order = factory( App\Droit\Shop\Order\Entities\Order::class)->make([
            'order_no' => '2015-00000003'
        ]);

        $shipping = factory(App\Droit\Shop\Shipping\Entities\Shipping::class)->make();
        $user     = factory(App\Droit\User\Entities\User::class)->make();

        $this->user->shouldReceive('find')->once()->andReturn($user);
        $this->order->shouldReceive('newOrderNumber')->once()->andReturn('2015-00000003');
        $this->order->shouldReceive('create')->once()->andReturn($order);

        $this->expectsJobs(App\Jobs\CreateOrderInvoice::class);

        $result = $worker->make($shipping);

    }

    /**
     * @expectedException App\Exceptions\OrderCreationException
     */
    public function testMakeNewOrderNotWorking()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderWorkerInterface');

        // Price 2.00 chf
        \Cart::add(55, 'Uno', 1, '100' , array('weight' => 155));

        $shipping = factory(App\Droit\Shop\Shipping\Entities\Shipping::class)->make();
        $cart     = factory(App\Droit\Shop\Cart\Entities\Cart::class)->make();
        $user     = factory(App\Droit\User\Entities\User::class)->make();

        $this->user->shouldReceive('find')->once()->andReturn($user);
        $this->order->shouldReceive('newOrderNumber')->once()->andReturn('2015-00000003');
        $this->order->shouldReceive('create')->once()->andReturn(null);
        $this->cart->shouldReceive('create')->once()->andReturn($cart);

        $result = $worker->make($shipping);

    }


    /* Order Worker for administration
     * */
    /**
     * @return void
     */
    public function testMakeNewOrderAdmin()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'weight' => 100,
            'price'  => 100,
        ]);

        $this->product->shouldReceive('find')->times(3)->andReturn($product);

        $commande = ['products' => [1,2,3], 'qty' => [1,1,1]];
        $expected = 300;
        $result   = $worker->total($commande);

        $this->assertEquals($expected,$result);
    }

    public function testMakeNewOrderAdmin2()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'weight' => 100,
            'price'  => 100,
        ]);

        $this->product->shouldReceive('find')->times(3)->andReturn($product);

        $commande = ['products' => [1,2,3], 'qty' => [1,3,1]];
        $expected = 500;
        $result   = $worker->total($commande);

        $this->assertEquals($expected,$result);
    }

    public function testMakeNewOrderAdminRabais()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'weight' => 100,
            'price'  => 100,
        ]);

        $this->product->shouldReceive('find')->times(3)->andReturn($product);

        $commande = ['products' => [1,2,3], 'qty' => [1,1,1], 'rabais' => [1 => 10]];
        $expected = 290;
        $result   = $worker->total($commande);

        $this->assertEquals($expected,$result);
    }

    public function testMakeNewOrderAdminRabaisGratuit()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'weight' => 100,
            'price'  => 100,
        ]);

        $this->product->shouldReceive('find')->times(3)->andReturn($product);

        $commande = ['products' => [1,2,3], 'qty' => [1,1,1], 'rabais' => [1 => 10], 'gratuit' => [0 => true]];
        $expected = 190;
        $result   = $worker->total($commande);

        $this->assertEquals($expected,$result);
    }

    public function testMakeNewOrderAdminWeight()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'weight' => 100,
            'price'  => 100,
        ]);

        $this->product->shouldReceive('find')->times(3)->andReturn($product);

        $commande = ['products' => [1,2,3], 'qty' => [1,1,1], 'rabais' => [1 => 10], 'gratuit' => [0 => true]];
        $expected = 300;
        $result   = $worker->total($commande, 'weight');

        $this->assertEquals($expected,$result);
    }

    public function testProductLoopWithGratuit()
    {
        $worker   = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $commande = ['products' => [1,2,3], 'qty' => [1,2,1], 'rabais' => [1 => 10], 'gratuit' => [0 => true]];

        $expected = [
            [1 => ['isFree' => 1]] ,
            [2 => ['isFree' => null]],
            [2 => ['isFree' => null]],
            [3 => ['isFree' => null]]
        ];

        $result = $worker->productIdFromForm($commande);

        $this->assertEquals($expected,$result);
    }
}
