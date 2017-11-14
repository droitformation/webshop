<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderMakerTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
    public function testCountProducts()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make([
            'order_no'    => '2016-00200003',
            'user_id'     => 1,
            'shipping_id' => 1,
            'payement_id' => 1,
            'amount'      => 9500,
        ]);

        $expect = [10 => 1, 21 => 2, 30 => 1];

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 10, 'weight' => 300, 'price' => 3000,]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 21, 'weight' => 150, 'price' => 2000,]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 21, 'weight' => 150, 'price' => 2000,]);
        $product4 = factory(\App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 30, 'weight' => 180, 'price' => 2500,]);

        $order->products = new \Illuminate\Database\Eloquent\Collection([$product1,$product2,$product3,$product4]);

        $count = $make->getQty($order);

        $this->assertEquals($expect, $count->toArray());

    }

    public function testMapProducts()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $order = [
            'products' => [0 => $prod1->id, 1 => $prod2->id],
            'qty'      => [0 => 2, 1 => 2],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1]
        ];

        // Calculations
        // product 1: (price * 2) / rabais 25% => 1500
        // product 2 is free
        $total = $make->total($order, $proprety = 'price');

        $this->assertEquals('1500', $total);
    }

    public function testMapProducts2()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $order = [
            'products' => [0 => $prod1->id, 1 => $prod2->id],
            'qty'      => [0 => 2, 1 => 2],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1],
            'price'    => [0 => 20]
        ];

        // Calculations
        // product 1: (new price * 2) / rabais 25% => 3000
        // product 2 is free

        $total = $make->total($order, $proprety = 'price');

        $this->assertEquals('3000', $total);
    }

    public function testMapWeightProducts()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '100';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '100';
        $prod2->price = '2000';
        $prod2->save();

        $order = [
            'products' => [0 => $prod1->id, 1 => $prod2->id],
            'qty'      => [0 => 2, 1 => 2],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1]
        ];

        // Calculations
        // product 1 weight: 2* 100 => 200
        // product 2 weight: 2* 100 => 200

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

        $factory = new \tests\factories\ObjectFactory();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $order = [
            'order' => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ]
        ];

        // Calculations products weight: 4000 => id: 2

        // 4000gr
        $shipping = $make->getShipping($order);

        $this->assertEquals(2, $shipping);
    }


    public function testCalculateFreeShipping()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $order = [
            'order' => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            'free' => 1
        ];

        // Calculations products weight: 4000 => id: 2
        // Free
        $shipping = $make->getShipping($order);

        $this->assertEquals(6, $shipping);
    }

    public function testProductLoopWithGratuit()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);
        $prod3 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $prod3->weight = '1000';
        $prod3->price = '2000';
        $prod3->save();

        $order = ['products' => [$prod1->id,$prod2->id,$prod3->id], 'qty' => [1,2,1], 'rabais' => [1 => 10], 'gratuit' => [0 => true], 'price' => [0 => 20]];
        // Beware array index start from 0, rabais is for the second product, gratuit for the first

        $expected = [
            ['id' => $prod1->id, 'isFree' => 1, 'rabais' => null, 'price' => 2000],
            ['id' => $prod2->id, 'isFree' => null, 'rabais' => 10, 'price' => null],
            ['id' => $prod2->id, 'isFree' => null, 'rabais' => 10, 'price' => null],
            ['id' => $prod3->id, 'isFree' => null, 'rabais' => null, 'price' => null]
        ];

        $result = $make->getProducts($order);

        $this->assertEquals($expected,$result);
    }

    public function testPrepareOrderDataFromAdmin()
    {
        $factory = new \tests\factories\ObjectFactory();
        $person  = $factory->makeUser();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $order = [
            'user_id' => $person->id,
            'order'   => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            //'free' => 1,
            'admin' => 1
        ];

        // Calculations products weight: 4000 => id: 2
        $products = [
            ['id' => $prod1->id, 'isFree' => null, 'rabais' => 25, 'price' => null],
            ['id' => $prod1->id, 'isFree' => null, 'rabais' => 25, 'price' => null],
            ['id' => $prod2->id, 'isFree' => 1, 'rabais' => null, 'price' => null],
            ['id' => $prod2->id, 'isFree' => 1, 'rabais' => null, 'price' => null]
        ];

        $data = [
            'user_id'     => $person->id,
            'amount'      => 1500.0,
            'order_no'    => '2016-00020003',
            'coupon_id'   => null,
            'shipping_id' => 2,
            'payement_id' => 1,
            'paquet'      => null,
            'products'    => $products
        ];

        $mockorder = \Mockery::mock('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->app->instance('App\Droit\Shop\Order\Repo\OrderInterface', $mockorder);

        // Mock before creating worker!!
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $mockorder->shouldReceive('newOrderNumber')->once()->andReturn('2016-00020003');

        $result = $make->prepare($order);

        $this->assertEquals($data,$result);
    }

    public function testInsertOrderFromAdmin()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();
        $person  = $factory->makeUser();

        $prod1 = $factory->makeProduct([]);
        $prod2 = $factory->makeProduct([]);

        $prod1->weight = '1000';
        $prod1->price = '1000';
        $prod1->save();

        $prod2->weight = '1000';
        $prod2->price = '2000';
        $prod2->save();

        $data = [
            'user_id'     => $person->id,
            'order_no'    => '2016-00020003',
            'amount'      => 1500.0,
            'coupon_id'   => null,
            'shipping_id' => 6,
            'payement_id' => 1,
            'products'    => $products =
                [
                    ['id' => $prod1->id, 'isFree' => null, 'rabais' => 25, 'price' => null],
                    ['id' => $prod1->id, 'isFree' => null, 'rabais' => 25, 'price' => null],
                    ['id' => $prod2->id, 'isFree' => 1, 'rabais' => null, 'price' => null],
                    ['id' => $prod2->id, 'isFree' => 1, 'rabais' => null, 'price' => null]
                ]
        ];

        $make->insert($data);

        $this->assertDatabaseHas('shop_orders', [
            'user_id'     => $person->id,
            'order_no'    => '2016-00020003',
            'shipping_id' => 6,
            'payement_id' => 1,
        ]);

    }
    public function testMakeOrderFromAdmin()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();
        $user = $factory->makeUser([]);

        $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make([
            'user_id'     => $user->id,
            'order_no'    => '2016-00020003',
            'amount'      => '1500',
            'coupon_id'   => null,
            'shipping_id' => 6,
            'payement_id' => 1,
        ]);

        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 2000,]);

        $order->products = new \Illuminate\Database\Eloquent\Collection([$prod1,$prod2,$prod1,$prod2]);

        $data = [
            'user_id' => $user->id,
            'order'   => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            'admin' => 1
        ];

        $make->make($data);

        $orders = new \App\Droit\Shop\Order\Entities\Order();
        $order  = $orders->orderBy('id','DESC')->take(1)->get()->first();

        $this->assertEquals($order->user_id,$order->user_id);
    }

    public function testPrepareOrderDataFromAdminPassShipping()
    {
        $make = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $factory = new \tests\factories\ObjectFactory();
        $user = $factory->makeUser([]);

        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 2000,]);

        $shipping = factory(\App\Droit\Shop\Shipping\Entities\Shipping::class)->create();

        $order = [
            'user_id' => $user->id,
            'order'   => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
            ],
            'admin' => 1
        ];

        $result = $make->prepare($order, $shipping);

        $this->assertEquals($shipping->id,$result['shipping_id']);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function testGetUserOrCreateAdresseFromOrder()
    {
        $make    = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        // Create a new adresse
        // Validation fails
        $data = ['adresse' => []];
        $make->getUser($data);
    }

    public function testGetUserOrCreateAdresseFromOrderMakeNewAccountWithData()
    {
        $make    = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        // Create new Adresse
        $new = ['adresse' => [
            'civilite_id' => 1,
            'first_name'  => 'Jane',
            'last_name'   => 'Doe',
            'company'     => 'DesignPond',
            'email'       => 'info@designpond.ch',
            'password'    =>  123456,
            'adresse'     => 'Rue du test 45',
            'npa'         => '2345',
            'ville'       => 'Bienne',
        ]];

        $response = $make->getUser($new);

        $users = new\App\Droit\User\Entities\User();
        $user = $users->orderBy('id','DESC')->take(1)->get()->first();

        $this->assertEquals(['user_id' => $user->id], $response);
    }
}
