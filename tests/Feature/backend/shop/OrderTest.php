<?php

namespace Tests\Feature\backend\shop;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testCreateOrderWithStep()
    {
        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000]);

        $data = [
            'user_id' => $user->id,
            'order' => [
                'products' => [0 => $product1->id, 1 => $product2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 50],
                'gratuit'  => [1 => 1]
            ],
            'admin'   => 1,
            'shipping_id' => 1,
            'adresse' => [],
            'tva'     => [],
            'message' => []
        ];

        $data_product = [
            [
                'product'  => $product1->id ,
                'qty'      => 2,
                'rabais'   => '25',
                'price'    => null,
                'gratuit'  => null,
                'prix'     => $product1->price_cents,
                'computed' => 10.00,
            ],
            [
                'product'  => $product2->id ,
                'qty'      => 2,
                'rabais'   => null,
                'price'    => null,
                'gratuit'  => 'oui',
                'prix'     => $product2->price_cents,
                'computed' => 0.00,
            ]
        ];

        $total = 20.00;

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);

        $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $order  = $model->getLast(1)->first();

        $this->assertEquals($total,$order->total_with_shipping);

        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();
    }

    public function testCreateOrderWithcalcul()
    {
        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 5900]);

        $data = [
            'user_id' => $user->id,
            'order' => [
                'products' => [0 => $product1->id],
                'qty'      => [0 => 124],
                'rabais'   => [],
                'gratuit'  => [],
                'price'    => [0 => 10.88]
            ],
            'admin'   => 1,
            'shipping_id' => 6,
            'adresse' => [],
            'tva'     => [],
            'message' => []
        ];

        $total = 1349.12;

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);

        $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $order  = $model->getLast(1)->first();

        $this->assertEquals($total,$order->total_with_shipping);

        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();
    }

    public function testCreateOrderWithStepFree()
    {
        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000,]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000,]);

        $data = [
            'user_id' => $user->id,
            'order' => [
                'products' => [0 => $product1->id, 1 => $product2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 50],
                'gratuit'  => [1 => 1]
            ],
            'admin'   => 1,
            'shipping_id' => 1,
            'free'    => 1,
            'adresse' => [],
            'tva'     => [],
            'message' => []
        ];

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);

        $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $order  = $model->getLast(1)->first();

        $this->assertEquals('10.00',$order->total_with_shipping);

        \DB::table('shop_orders')->truncate();
    }

    /**
     * @return void
     */
    public function testFilterSendOrderss()
    {
        // Prepare and create some orders
        $make   = new \tests\factories\ObjectFactory();

        $pending = $make->order(5);
        $send    = $make->order(3);

        $start = \Carbon\Carbon::now()->startOfMonth()->subDay()->format('Y-m-d');
        $end   = \Carbon\Carbon::now()->endOfMonth()->addDay()->format('Y-m-d');

        // set 3 with date sent
        $make->updateOrder($send, ['column' => 'send_at', 'date' => '2016-09-10']);

        // filter to get all send orders
        $response = $this->call('POST', 'admin/orders', [
            'period' => [
                'start' => $start,
                'end'   => $end,
            ],
            'send'  => 'send'
        ]);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals(3, $result->count());

        // filter to get non sent orders
        $response = $this->call('POST', 'admin/orders', ['start' => $start, 'end' => $end, 'send' => 'pending']);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals(5, $result->count());

        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();
    }

    /**
     * @return void
     */
    public function testFilterPayedOrderss()
    {
        // create some orders
        $make   = new \tests\factories\ObjectFactory();

        $payed   = $make->order(5);
        $pending = $make->order(3);

        $start = \Carbon\Carbon::now()->startOfMonth()->subDay()->format('Y-m-d');
        $end   = \Carbon\Carbon::now()->endOfMonth()->addDay()->format('Y-m-d');

        // set 5 to payed status
        $make->updateOrder($payed, ['column' => 'payed_at', 'date' => '2016-09-10']);

        $response = $this->call('POST', 'admin/orders', [
            'period' => [
                'start' => $start,
                'end'   => $end,
            ],
            'status' => 'payed']);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals(5, $result->count());

        $response = $this->call('POST', 'admin/orders', [
            'period' => [
                'start' => $start,
                'end'   => $end,
            ],'status' => 'pending']);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals(3, $result->count());

        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();
    }

    public function testUpdateOrderWithCoupon()
    {
        $make  = new \tests\factories\ObjectFactory();

        // Create on order
        $orders = $make->order(1);
        $order  = $orders->first();

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value' => 10, // -10
            'title' => 'PHP',
            'type'  => 'priceshipping',
            'expire_at' => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);

        $coupon->products()->saveMany($order->products);

        $total = $order->products->map(function ($product, $key) use ($coupon) {
            return ($product->price_normal - $coupon->value) * 100;
        })->sum();

        $data = [
            'id'          => $order->id,
            'user_id'     => $order->user_id,
            'shipping_id' => 1,
            'coupon_id'   => $coupon->id
        ];

        $this->call('PUT', 'admin/order/'.$order->id, $data);
        $response = $this->get( 'admin/order/'.$order->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $result  = $content['order'];

        $this->assertEquals(6, $result->shipping_id);
        $this->assertEquals($total, $result->amount);

        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();
    }

    public function testSortPeriodNoResult()
    {
        // Prepare and create some orders
        $make   = new \tests\factories\ObjectFactory();
        $orders = $make->order(3);

        // set 3 with date sent
        $make->updateOrder($orders, ['column' => 'send_at', 'date' => '2016-09-10']);

        // filter to get all send orders
        $response = $this->call('POST', 'admin/orders', [
            'period' => [
                'start' => '2016-10-08',
                'end'   => '2016-10-18',
            ],
            'send'  => 'send'
        ]);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals(0, $result->count());

        \DB::table('shop_orders')->truncate();
        \DB::table('shop_paquets')->truncate();
        \DB::table('shop_order_paquets')->truncate();
    }

    public function testCreateOrderWithReferences()
    {
        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000]);

        $data = [
            'user_id' => $user->id,
            'order' => [
                'products' => [0 => $product1->id, 1 => $product2->id],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 50],
                'gratuit'  => [1 => 1]
            ],
            'admin'   => 1,
            'reference_no'   => 'Rf_2019_depond',
            'transaction_no' => '29_10_124',
            'shipping_id' => 1,
            'adresse' => [],
            'tva'     => [],
            'message' => []
        ];

        $data_product = [
            [
                'product'  => $product1->id ,
                'qty'      => 2,
                'rabais'   => '25',
                'price'    => null,
                'gratuit'  => null,
                'prix'     => $product1->price_cents,
                'computed' => 10.00,
            ],
            [
                'product'  => $product2->id ,
                'qty'      => 2,
                'rabais'   => null,
                'price'    => null,
                'gratuit'  => 'oui',
                'prix'     => $product2->price_cents,
                'computed' => 0.00,
            ]
        ];

        $total = 20.00;

        session()->put('reference_no', 'Ref_2019_designpond');
        session()->put('transaction_no', '2109_10_19824');

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);

        $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $order  = $model->getLast(1)->first();

        $this->assertEquals($total,$order->total_with_shipping);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
        ]);

    }
}
