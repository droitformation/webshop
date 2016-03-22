<?php

class ProductTest extends \TestCase {

    protected $product;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->mock);

    }


    /**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCountProducts()
	{
        $product = new \App\Droit\Shop\Order\Worker\Order($this->mock);

		$order = factory( App\Droit\Shop\Order\Entities\Order::class)->make([
			'order_no'    => '2016-00200003',
			'user_id'     => 1,
			'shipping_id' => 1,
			'payement_id' => 1,
			'amount'      => 9500,
		]);

        $expect = [10 => 1, 21 => 2, 30 => 1];

		$product1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 10, 'weight' => 300, 'price' => 3000,]);
        $product2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 21, 'weight' => 150, 'price' => 2000,]);
        $product3 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 21, 'weight' => 150, 'price' => 2000,]);
        $product4 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 30, 'weight' => 180, 'price' => 2500,]);

		$order->products = new Illuminate\Database\Eloquent\Collection([$product1,$product2,$product3,$product4]);

        $count = $product->getQty($order);

        $this->assertEquals($expect, $count->toArray());

	}

    public function testMapProducts()
    {
        $product = new \App\Droit\Shop\Order\Worker\Order($this->mock);

        $order = [
            'products' => [0 => 2, 1 => 291],
            'qty'      => [0 => 2, 1 => 2],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1]
        ];

        // Calculations
        // product 1: (price * 2) / rabais 25% => 1500
        // product 2 is free

        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 100, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 100, 'price'  => 2000,]);

        $this->mock->shouldReceive('find')->once()->andReturn($prod1);
        $this->mock->shouldReceive('find')->once()->andReturn($prod2);

        $total = $product->total($order, $proprety = 'price');

        $this->assertEquals('1500', $total);
    }

    public function testMapWeightProducts()
    {
        $product = new \App\Droit\Shop\Order\Worker\Order($this->mock);

        $order = [
            'products' => [0 => 2, 1 => 291],
            'qty'      => [0 => 2, 1 => 2],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1]
        ];

        // Calculations
        // product 1 weight: 2* 100 => 200
        // product 2 weight: 2* 100 => 200

        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 100, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 100, 'price'  => 2000,]);

        $this->mock->shouldReceive('find')->once()->andReturn($prod1);
        $this->mock->shouldReceive('find')->once()->andReturn($prod2);

        $totalweight = $product->total($order, $proprety = 'weight');

        $this->assertEquals('400', $totalweight);
    }

    public function testProductsId()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , ['weight' => 155]);
        \Cart::add(55, 'Uno', 1, '12' , ['weight' => 155]);
        \Cart::add(56, 'Duo', 1, '34' , ['weight' => 255]);
        \Cart::add(57, 'tre', 1, '35' , ['weight' => 125]);

        $cart = \Cart::content();

        $product = new \App\Droit\Shop\Order\Worker\Order($this->mock);

        $ids = $product->getProductsId($cart);

        $expect = [0 => 55, 1 => 55, 2 => 56, 3 => 57];

        $this->assertEquals($expect, $ids);
    }

}
