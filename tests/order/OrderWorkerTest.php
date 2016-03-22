<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class OrderWorkerTest extends TestCase {

    use WithoutMiddleware;

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

        $this->order = Mockery::mock('App\Droit\Shop\Order\Repo\OrderAdminInterface');
        $this->app->instance('App\Droit\Shop\Order\Repo\OrderAdminInterface', $this->order);

        $this->cart = Mockery::mock('App\Droit\Shop\Cart\Repo\CartInterface');
        $this->app->instance('App\Droit\Shop\Cart\Repo\CartInterface', $this->cart);

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

        $order = factory( App\Droit\Shop\Order\Entities\Order::class)->make([
            'order_no' => '2015-00000003'
        ]);

        $product1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'id'     => 1,
            'weight' => 100,
            'price'  => 100,
        ]);

        $product2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'id'     => 2,
            'weight' => 100,
            'price'  => 100,
        ]);

        $order->products = new Illuminate\Database\Eloquent\Collection([$product1,$product2]);

        $shipping = factory(App\Droit\Shop\Shipping\Entities\Shipping::class)->make();
        $user     = factory(App\Droit\User\Entities\User::class)->make();

        $this->user->shouldReceive('find')->once()->andReturn($user);
/*        $this->order->shouldReceive('newOrderNumber')->once()->andReturn('2015-00000003');
        $this->order->shouldReceive('create')->once()->andReturn($order);*/
       // $this->order->shouldReceive('resetQty')->with($order,'-')->once();
        $this->product->shouldReceive('find')->twice()->andReturn($product2);

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

        $product2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'id'     => 2,
            'weight' => 100,
            'price'  => 100,
        ]);

        $this->user->shouldReceive('find')->once()->andReturn($user);
        $this->product->shouldReceive('find')->twice()->andReturn($product2);
/*        $this->order->shouldReceive('newOrderNumber')->once()->andReturn('2015-00000003');
        $this->order->shouldReceive('create')->once()->andReturn(null);
        $this->cart->shouldReceive('create')->once()->andReturn($cart);*/

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

        $commande = [ 'products' => [1,2,3], 'qty' => [1,2,1], 'rabais' => [1 => 10], 'gratuit' => [0 => true]];
        // Beware array index start from 0, rabais is for the second product, gratuit for the first

        $expected = [
            [1 => ['isFree' => 1]],
            [2 => ['isFree' => null,'rabais' => 10]],
            [2 => ['isFree' => null,'rabais' => 10]],
            [3 => ['isFree' => null]]
        ];

        $result = $worker->productIdFromForm($commande);

        $this->assertEquals($expected,$result);
    }

    public function testFilterArray()
    {
        $worker = \App::make('App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface');

        $items  = [
            0 => 1,
            1 => null,
            2 => ''
        ];

        $expected = [ 0 => 1];

        $result = $worker->removeEmpty($items);

        $this->assertEquals($expected,$result);
    }

}
