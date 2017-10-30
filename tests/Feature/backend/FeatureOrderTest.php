<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $maker;
    protected $order;
    protected $product;
    protected $generator;
    protected $stock;
    protected $account;

    public function setUp()
    {
        parent::setUp();
        $this->artisan("db:seed");

        $this->user = \Mockery::mock('App\Droit\User\Repo\UserInterface');
        $this->app->instance('App\Droit\User\Repo\UserInterface', $this->user);

        $this->account = \Mockery::mock('App\Droit\User\Worker\AccountWorkerInterface');
        $this->app->instance('App\Droit\User\Worker\AccountWorkerInterface', $this->account);

        //$this->maker = \Mockery::mock('App\Droit\Shop\Order\Worker\OrderMakerInterface');
       // $this->app->instance('App\Droit\Shop\Order\Worker\OrderMakerInterface', $this->maker);

       // $this->order = \Mockery::mock('App\Droit\Shop\Order\Repo\OrderInterface');
        //$this->app->instance('App\Droit\Shop\Order\Repo\OrderInterface', $this->order);

        //$this->product = \Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
       // $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product);

        //$this->generator = \Mockery::mock('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        //$this->app->instance('App\Droit\Generate\Pdf\PdfGeneratorInterface', $this->generator);

        //$this->stock = \Mockery::mock('App\Droit\Shop\Stock\Repo\StockInterface');
        //$this->app->instance('App\Droit\Shop\Stock\Repo\StockInterface', $this->stock);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testSeePageForNewOrder()
    {
        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 2000, 'hidden' => 1]);

        $response = $this->get('/admin/order/create');

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['products'];

        $this->assertEquals($result->count(),1);
    }

    public function testCreateNewOrderViaAdminPassValidationWithUser()
    {
        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 2000, 'hidden' => 1]);

        $user = factory(\App\Droit\User\Entities\User::class)->create();

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

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);
        $response->isRedirect('/admin/orders');

        $this->assertDatabaseHas('shop_orders', [
            'user_id' => $user->id,
            'admin'   => 1
        ]);

    }
    /*
        public function testValidationPassWithAccountCreation()
        {
            $data = [
                'adresse' => [
                    'civilite_id' => 1,
                    'first_name'  => 'Jane',
                    'last_name'   => 'Doe',
                    'company'     => 'DesignPond',
                    'email'       => 'info@designpond.ch',
                    'password'    => 123456,
                    'adresse'     => 'Rue du test 45',
                    'npa'         => '2345',
                    'ville'       => 'Bienne',
                ],
                'order' => [
                    'products' => [0 => 22, 1 => 12],
                    'qty'      => [0 => 2, 1 => 2],
                    'rabais'   => [0 => 25],
                    'gratuit'  => [1 => 1]
                ],
                'admin' => 1,
            ];

            $factory = new \tests\factories\ObjectFactory();
            $user = $factory->user();

            $this->account->shouldReceive('createAccount')->andReturn($user);

            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make(['user_id' => $user->id]);

            $order = \Mockery::mock($order);

            $this->maker->shouldReceive('make')->once()->andReturn($order);
            $this->order->shouldReceive('update')->once();

            $response = $this->call('POST', '/admin/order', $data);

            $response->isRedirect('/admin/orders');
        }

        public function testListOrdersInAdmin()
        {
            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make([
                'user_id'     => 710,
                'order_no'    => '2016-00020003',
                'amount'      => '1500',
                'coupon_id'   => null,
                'shipping_id' => 6,
                'payement_id' => 1,
                'created_at'  => \Carbon\Carbon::now(),
                'updated_at'  => \Carbon\Carbon::now(),
            ]);

            $orders    = new \Illuminate\Database\Eloquent\Collection([$order]);
            $cancelled = new \Illuminate\Database\Eloquent\Collection([]);

            $this->order->shouldReceive('getPeriod')->once()->andReturn($orders);
            $this->order->shouldReceive('getTrashed')->once()->andReturn($cancelled);

            $this->get('/admin/orders');
        }

        public function testValidationFailsWithoutUserOrAdresse()
        {
            $data = [
                'user_id' => null,
                'order'   => [
                    'products' => [0 => 22, 1 => 12],
                    'qty'      => [0 => 2, 1 => 2],
                    'rabais'   => [0 => 25],
                    'gratuit'  => [1 => 1]
                ],
                'admin' => 1,
                'adresse' => []
            ];

            $response = $this->call('POST', '/admin/order/verification', $data);

            $response->isRedirect('/admin/order/create');
            $response->assertSessionHas('old_products');
            $response->assertSessionHas('adresse');
        }

        public function testValidationFailsWithoutOrderProducts()
        {
            $data = [
                'user_id' => 710,
                'order'   => [
                    'products' => [],
                    'qty'      => [0 => 2, 1 => 2],
                    'rabais'   => [0 => 25],
                    'gratuit'  => [1 => 1]
                ],
                'admin' => 1,
                'adresse' => []
            ];

            $response = $this->call('POST', '/admin/order/verification', $data);

            $response->isRedirect('/admin/order/create');
            $response->assertSessionHas('old_products');
            $response->assertSessionHas('adresse');
        }

        public function testUpdateOrder()
        {
            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make(['id' => 1]);
            $this->generator->shouldReceive('factureOrder')->once();
            $this->maker->shouldReceive('updateOrder')->once()->andReturn(['id' => 1]);
            $this->order->shouldReceive('update')->twice()->andReturn($order);

            $response = $this->call('PUT', '/admin/order/1', ['status' => 'payed', 'tva' => [] , 'message' => []]);

            $response->isRedirect('/admin/order/1');
        }

        public function testEditOrderViaAjax()
        {
            $input = [
                'pk'    => 1,
                'value' => '2016-05-12',
                'name'  => 'payed_at'
            ];

            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make(['status' => 'payed']);
            $this->order->shouldReceive('updateDate')->once()->andReturn($order);

            $this->json('POST', '/admin/order/edit', $input)
                ->assertJsonFragment([
                    'OK'    => 200,
                    'etat'  => 'Payé',
                    'color' => 'success'
                ]);
        }

        public function testDeleteOrderAndResetQty()
        {
            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make(['id' => 1]);

            $this->order->shouldReceive('find')->once()->andReturn($order);
            $this->maker->shouldReceive('resetQty')->once();
            $this->order->shouldReceive('delete')->once();

            $response = $this->call('DELETE', '/admin/order/1');

            $response->isRedirect('/admin/orders');
        }

        public function testRestoreOrder()
        {
            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->make(['id' => 1, 'deleted_at' => \Carbon\Carbon::now()]);

            $this->order->shouldReceive('restore')->once();

            $response = $this->call('POST', '/admin/order/restore/1');

            $response->isRedirect('/admin/orders');
        }

        public function testVerificationAndReturnCreatePagePage()
        {
            $make = new \tests\factories\ObjectFactory();
            $user = $make->makeUser();

            $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();
            $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

            $data = [
                'user_id' => $user->id,
                'order' => [
                    'products' => [0 => $product1->id, 1 => $product2->id],
                    'qty'      => [0 => 2, 1 => 2],
                    'rabais'   => [0 => 25],
                    'gratuit'  => [1 => 1]
                ],
                'admin'   => 1,
                'shipping_id' => 1,
                'adresse' => [],
                'tva'     => [],
                'message' => []
            ];

            $money = new \App\Droit\Shop\Product\Entities\Money;

            $price1 = $product1->price_cents - ($product1->price_cents * 0.25);
            $price1 = $price1 * 2;

            $data_product = [
                [
                    'product'  => $product1->id ,
                    'qty'      => 2,
                    'rabais'   => '25',
                    'price'    => null,
                    'gratuit'  => null,
                    'prix'     => $product1->price_cents,
                    'computed' => $money->format($price1),
                ],
                [
                    'product'  => $product2->id ,
                    'qty'      => 2,
                    'rabais'   => null,
                    'price'    => null,
                    'gratuit'  => 'oui',
                    'prix'     => $product2->price_cents,
                    'computed' => $money->format(0),
                ]
            ];

            $total = $product1->price_cents + $product2->price_cents + 10;

            $this->user->shouldReceive('find')->once()->andReturn($user);
            $this->product->shouldReceive('find')->once()->andReturn($product1);
            $this->product->shouldReceive('find')->once()->andReturn($product2);
            $this->maker->shouldReceive('total')->once()->andReturn($total);;

            $response = $this->call('POST', '/admin/order/verification', $data);

            $content = $response->getOriginalContent();
            $result  = $content['data'];

            $this->assertEquals($result, $data);

            // Correction
            $this->product->shouldReceive('find')->times(1)->andReturn($product1);
            $this->product->shouldReceive('find')->times(1)->andReturn($product2);

            // Go back to creation
            $response = $this->call('POST', '/admin/order/correction', ['data' => json_encode($data)]);

            $response->isRedirect('admin/order/create');

            $products    = $this->app['session.store']->get('old_products');
            $shipping_id = $this->app['session.store']->get('shipping_id');
            $user_id     = $this->app['session.store']->get('user_id');

            $response->assertSessionHas('old_products');
            $this->assertEquals($data_product,$products);
            $this->assertEquals($user->id,$user_id);
            $this->assertEquals(1,$shipping_id);
        }*/
}
