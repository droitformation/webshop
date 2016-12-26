<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderMakerTest extends \TestCase {

    use DatabaseTransactions;

    protected $mock;
    protected $mockship;
    protected $mockcart;
    protected $mockorder;
    protected $generator;
    protected $adresse;
    protected $stock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->mock);

        $this->mockship = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');

        $this->mockcart = Mockery::mock('App\Droit\Shop\Cart\Repo\CartInterface');
        $this->app->instance('App\Droit\Shop\Cart\Repo\CartInterface', $this->mockcart);

        $this->mockorder = Mockery::mock('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->app->instance('App\Droit\Shop\Order\Repo\OrderInterface', $this->mockorder);

        $this->generator = Mockery::mock('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->app->instance('App\Droit\Generate\Pdf\PdfGeneratorInterface', $this->generator);

        $this->adresse = Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
        $this->app->instance('App\Droit\Adresse\Repo\AdresseInterface', $this->adresse);

        $this->stock = Mockery::mock('App\Droit\Shop\Stock\Repo\StockInterface');
        $this->app->instance('App\Droit\Shop\Stock\Repo\StockInterface', $this->stock);

    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCountProducts()
	{
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

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

        $count = $make->getQty($order);

        $this->assertEquals($expect, $count->toArray());

	}

    public function testMapProducts()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

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

        $total = $make->total($order, $proprety = 'price');

        $this->assertEquals('1500', $total);
    }

    public function testMapProducts2()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = [
            'products' => [0 => 2, 1 => 291],
            'qty'      => [0 => 2, 1 => 2],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1],
            'price'    => [0 => 20]
        ];

        // Calculations
        // product 1: (new price * 2) / rabais 25% => 3000
        // product 2 is free

        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 100, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 100, 'price'  => 2000,]);

        $this->mock->shouldReceive('find')->once()->andReturn($prod1);
        $this->mock->shouldReceive('find')->once()->andReturn($prod2);

        $total = $make->total($order, $proprety = 'price');

        $this->assertEquals('3000', $total);
    }

    public function testMapWeightProducts()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

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

        $totalweight = $make->total($order, $proprety = 'weight');

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

        $product = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $ids = $product->getProductsCart($cart);

        $expect = [
            ['id' => 55],
            ['id' => 55],
            ['id' => 56],
            ['id' => 57]
        ];

        $this->assertEquals($expect, $ids);

        \Cart::destroy();
    }

    /*
         $shop_shipping = [
          ['id' => '1','title' => 'Envoi par Poste <2kg','value' => '2000','price' => '1000','type' => 'poids'],
          ['id' => '2','title' => 'Envoi par Poste <5kg','value' => '5000','price' => '1100','type' => 'poids'],
          ['id' => '3','title' => 'Envoi par Poste <10kg','value' => '10000','price' => '1400','type' => 'poids'],
          ['id' => '4','title' => 'Envoi par Poste <20kg','value' => '20000','price' => '1900','type' => 'poids'],
          ['id' => '5','title' => 'Envoi par Poste <30kg','value' => '30000','price' => '2600','type' => 'poids'],
          ['id' => '6','title' => 'Frais de port gratuit','value' => '0','price' => '0','type' => 'gratuit']
    ];
    */
    public function testCalculateShipping()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = [
            'order' => [
                'products' => [0 => 22, 1 => 12],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ]
        ];

        // Calculations products weight: 4000 => id: 2
        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 1000, 'price'  => 2000,]);

        $this->mock->shouldReceive('find')->once()->andReturn($prod1);
        $this->mock->shouldReceive('find')->once()->andReturn($prod2);

        // 4000gr
        $shipping = $make->getShipping($order);

        $this->assertEquals(2, $shipping);
    }


    public function testCalculateFreeShipping()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = [
            'order' => [
                'products' => [0 => 22, 1 => 12],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            'free' => 1
        ];

        // Calculations products weight: 4000 => id: 2
        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 1000, 'price'  => 2000,]);

        $this->mock->shouldReceive('find')->once()->andReturn($prod1);
        $this->mock->shouldReceive('find')->once()->andReturn($prod2);

        // Free
        $shipping = $make->getShipping($order);

        $this->assertEquals(6, $shipping);
    }

    public function testProductLoopWithGratuit()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = ['products' => [1,2,3], 'qty' => [1,2,1], 'rabais' => [1 => 10], 'gratuit' => [0 => true], 'price' => [0 => 20]];
        // Beware array index start from 0, rabais is for the second product, gratuit for the first

        $expected = [
            ['id' => 1, 'isFree' => 1, 'rabais' => null, 'price' => 2000],
            ['id' => 2, 'isFree' => null, 'rabais' => 10, 'price' => null],
            ['id' => 2, 'isFree' => null, 'rabais' => 10, 'price' => null],
            ['id' => 3, 'isFree' => null, 'rabais' => null, 'price' => null]
        ];

        $result = $make->getProducts($order);

        $this->assertEquals($expected,$result);
    }

    public function testPrepareOrderDataFromAdmin()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = [
            'user_id' => 710,
            'order'   => [
                'products' => [0 => 22, 1 => 12],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            //'free' => 1,
            'admin' => 1
        ];

        // Calculations products weight: 4000 => id: 2
        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 1000, 'price'  => 2000,]);

        $this->mock->shouldReceive('find')->twice()->andReturn($prod1);
        $this->mock->shouldReceive('find')->twice()->andReturn($prod2);

        $products = [
            ['id' => 22, 'isFree' => null, 'rabais' => 25, 'price' => null],
            ['id' => 22, 'isFree' => null, 'rabais' => 25, 'price' => null],
            ['id' => 12, 'isFree' => 1, 'rabais' => null, 'price' => null],
            ['id' => 12, 'isFree' => 1, 'rabais' => null, 'price' => null]
        ];

        $data = [
            'user_id'     => 710,
            'order_no'    => '2016-00020003',
            'amount'      => '1500',
            'coupon_id'   => null,
            'shipping_id' => 2,
            'payement_id' => 1,
            'products'    => $products
        ];

        $this->mockorder->shouldReceive('newOrderNumber')->once()->andReturn('2016-00020003');

        $result = $make->prepare($order);

        $this->assertEquals($data,$result);
    }

    public function testInsertOrderFromAdmin()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $data = [
            'user_id'     => 710,
            'order_no'    => '2016-00020003',
            'amount'      => '1500',
            'coupon_id'   => null,
            'shipping_id' => 6,
            'payement_id' => 1,
            'products'    => $products =
                [
                    ['id' => 22, 'isFree' => null, 'rabais' => 25, 'price' => null],
                    ['id' => 22, 'isFree' => null, 'rabais' => 25, 'price' => null],
                    ['id' => 12, 'isFree' => 1, 'rabais' => null, 'price' => null],
                    ['id' => 12, 'isFree' => 1, 'rabais' => null, 'price' => null]
                ]
        ];

        $this->mockorder->shouldReceive('create')->once();

        $make->insert($data);

    }

    public function testMakeOrderFromAdmin()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = factory(App\Droit\Shop\Order\Entities\Order::class)->make([
            'user_id'     => 710,
            'order_no'    => '2016-00020003',
            'amount'      => '1500',
            'coupon_id'   => null,
            'shipping_id' => 6,
            'payement_id' => 1,
        ]);

        $prod1 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 22, 'weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 12, 'weight' => 1000, 'price'  => 2000,]);

        $order->products = new Illuminate\Database\Eloquent\Collection([$prod1,$prod2,$prod1,$prod2]);

        $data = [
            'user_id' => 710,
            'order'   => [
                'products' => [0 => 22, 1 => 12],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            'admin' => 1
        ];

        $this->mock->shouldReceive('find')->twice()->andReturn($prod1);
        $this->mock->shouldReceive('find')->twice()->andReturn($prod2);
        $this->mockorder->shouldReceive('newOrderNumber')->once()->andReturn('2016-00020003');
        $this->mockorder->shouldReceive('create')->once()->andReturn($order);
        $this->mock->shouldReceive('sku')->times(2);
        $this->stock->shouldReceive('create')->times(2);
        $this->generator->shouldReceive('factureOrder')->once();

        $make->make($data);

    }

    public function testGetUserOrCreateAdresseFromOrder()
    {
        $make    = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->make(['id' => 2]);

        // Create a new adresse
        $data = ['adresse' => []];

        $this->adresse->shouldReceive('create')->once()->andReturn($adresse);

        $make->getUser($data);

        // Adresse id
        $data2    = ['adresse_id' => 12];
        $response = $make->getUser($data2);

        $this->assertEquals($data2, $response);

        // User id
        $data3    = ['user_id' => 12];
        $response = $make->getUser($data3);

        $this->assertEquals($data3, $response);

    }
}
