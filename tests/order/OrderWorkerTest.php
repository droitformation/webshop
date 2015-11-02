<?php

class OrderWorkerTest extends TestCase {

    protected $mock;
    protected $user;
    protected $order;
    protected $cart;

    public function setUp()
    {
        parent::setUp();

        $this->user = Mockery::mock('App\Droit\User\Repo\UserInterface');
        $this->app->instance('App\Droit\User\Repo\UserInterface', $this->user);

        $this->order = Mockery::mock('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->app->instance('App\Droit\Shop\Order\Repo\OrderInterface', $this->order);

        $this->cart = Mockery::mock('App\Droit\Shop\Cart\Repo\CartInterface');
        $this->app->instance('App\Droit\Shop\Cart\Repo\CartInterface', $this->cart);

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
	public function testNewOrderNumber()
	{

        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderWorkerInterface');

        $order = factory( App\Droit\Shop\Order\Entities\Order::class)->make([
            'order_no' => '2015-00000003'
        ]);

        $this->order->shouldReceive('maxOrder')->once()->andReturn($order);
        $response = $worker->newOrderNumber();

        $this->assertEquals('2015-00000004', $response);
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
        $max      = factory(App\Droit\Shop\Order\Entities\Order::class)->make([ 'order_no' => '2015-00000003' ]);

        $this->user->shouldReceive('find')->once()->andReturn($user);
        $this->order->shouldReceive('maxOrder')->once()->andReturn($max);
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
        $max      = factory(App\Droit\Shop\Order\Entities\Order::class)->make(['order_no' => '2015-00000003']);

        $this->user->shouldReceive('find')->once()->andReturn($user);
        $this->order->shouldReceive('maxOrder')->once()->andReturn($max);
        $this->order->shouldReceive('create')->once()->andReturn(null);
        $this->cart->shouldReceive('create')->once()->andReturn($cart);

        $result = $worker->make($shipping);

    }


}
