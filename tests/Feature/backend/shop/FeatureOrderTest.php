<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureOrderTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $account;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->account = \Mockery::mock('App\Droit\User\Worker\AccountWorkerInterface');
        $this->app->instance('App\Droit\User\Worker\AccountWorkerInterface', $this->account);

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
        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 2000, 'hidden' => 1]);

        $response = $this->get('/admin/order/create');

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['products'];

        $this->assertEquals($result->count(), 1);
    }

    public function testCreateNewOrderViaAdminPassValidationWithUser()
    {
        \DB::table('shop_orders')->truncate();

        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 2000, 'hidden' => 1]);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser([]);

        $data = [
            'user_id' => $user->id,
            'order' => [
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

        $response = $this->get('admin/orders');

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $orders = $content['orders'];

        $this->assertEquals(1, $orders->count());
        $this->assertEquals(4, $orders->first()->products->count());

    }

    public function testValidationPassWithAccountCreation()
    {
        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 1000,]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 2000, 'hidden' => 1]);

        $data = [
            'adresse' => [
                'civilite_id' => 1,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'company' => 'DesignPond',
                'email' => 'info@designpond.ch',
                'password' => 123456,
                'adresse' => 'Rue du test 45',
                'npa' => '2345',
                'ville' => 'Bienne',
            ],
            'order' => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty' => [0 => 2, 1 => 2],
                'rabais' => [0 => 25],
                'gratuit' => [1 => 1]
            ],
            'admin' => 1,
        ];

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser([]);

        $this->account->shouldReceive('createAccount')->andReturn($user);

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);

        $response->isRedirect('/admin/orders');

        $response = $this->get('admin/orders');

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $orders = $content['orders'];

        $this->assertEquals(1, $orders->count());
        $this->assertEquals(4, $orders->first()->products->count());
    }

    public function testListOrdersInAdmin()
    {
        $make = new \tests\factories\ObjectFactory();
        $orders = $make->order(3);

        // Delete one
        $first = $orders->shift();
        $first->delete();

        $response = $this->get('/admin/orders');

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $orders = $content['orders'];

        $this->assertEquals(2, $orders->count());
    }

    public function testValidationFailsWithoutUserOrAdresse()
    {
        $data = [
            'user_id' => null,
            'order' => [
                'products' => [0 => 22, 1 => 12],
                'qty' => [0 => 2, 1 => 2],
                'rabais' => [0 => 25],
                'gratuit' => [1 => 1]
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
            'order' => [
                'products' => [],
                'qty' => [0 => 2, 1 => 2],
                'rabais' => [0 => 25],
                'gratuit' => [1 => 1]
            ],
            'admin' => 1,
            'adresse' => []
        ];

        $response = $this->call('POST', '/admin/order/verification', $data);

        $response->isRedirect('/admin/order/create');
        $response->assertSessionHas('old_products');
        $response->assertSessionHas('adresse');
    }

    public function testEditOrderViaAjax()
    {
        $make = new \tests\factories\ObjectFactory();
        $orders = $make->order(1);
        $order = $orders->first();

        $this->assertNotEquals($order->payed_at, '206-05-12');

        $input = [
            'pk' => $order->id,
            'value' => '2016-05-12',
            'name' => 'payed_at'
        ];

        $this->json('POST', '/admin/order/edit', $input)
            ->assertJsonFragment([
                'OK' => 200,
                'etat' => 'Payé',
                'color' => 'success'
            ]);

        $response = $this->get('/admin/order/' . $order->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $order = $content['order'];

        $this->assertEquals($order->payed_at->toDateString(), '2016-05-12');
    }

    public function testDeleteOrderAndResetQty()
    {
        $prod1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 1000, 'sku' => 5]);
        $prod2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price' => 2000]);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser([]);

        $data = [
            'user_id' => $user->id,
            'order' => [
                'products' => [0 => $prod1->id, 1 => $prod2->id],
                'qty' => [0 => 2, 1 => 2],
                'rabais' => [0 => 25],
                'gratuit' => [1 => 1]
            ],
            'admin' => 1
        ];

        $response = $this->call('POST', '/admin/order', ['data' => json_encode($data)]);
        $response = $this->get('admin/orders');

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $orders = $content['orders'];

        $order = $orders->first();
        $product = $order->products->first();

        $this->assertEquals(3, $product->sku); // inital is 5 - 2 from order = 3

        $response = $this->call('DELETE', '/admin/order/' . $order->id);
        $response->isRedirect('/admin/orders');

        $response = $this->get('/admin/product/' . $product->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $product = $content['product'];

        $this->assertEquals(5, $product->sku); // inital is 5 - 2 from order = 3
    }

    public function testRestoreOrder()
    {
        $make = new \tests\factories\ObjectFactory();
        $orders = $make->order(1);
        $order = $orders->first();

        $response = $this->call('DELETE', '/admin/order/' . $order->id);

        $this->assertDatabaseMissing('shop_orders', [
            'id' => $order->id,
            'deleted_at' => null
        ]);

        $response = $this->call('POST', '/admin/order/restore/' . $order->id);
        $response->isRedirect('/admin/orders');

        $response = $this->get('/admin/order/' . $order->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $order = $content['order'];

        $this->assertDatabaseHas('shop_orders', [
            'id' => $order->id,
            'deleted_at' => null
        ]);
    }

    public function testVerificationAndReturnCreatePagePage()
    {

        $user_mock = \Mockery::mock('App\Droit\User\Repo\UserInterface');
        $this->app->instance('App\Droit\User\Repo\UserInterface', $user_mock);

        $maker_mock = \Mockery::mock('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $this->app->instance('App\Droit\Shop\Order\Worker\OrderMakerInterface', $maker_mock);

        $product_mock = \Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $product_mock);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

        $data = [
            'user_id' => $user->id,
            'order' => [
                'products' => [0 => $product1->id, 1 => $product2->id],
                'qty' => [0 => 2, 1 => 2],
                'rabais' => [0 => 25],
                'gratuit' => [1 => 1]
            ],
            'admin' => 1,
            'shipping_id' => 1,
            'adresse' => [],
            'tva' => [],
            'message' => []
        ];

        $money = new \App\Droit\Shop\Product\Entities\Money;

        $price1 = $product1->price_cents - ($product1->price_cents * 0.25);
        $price1 = $price1 * 2;

        $data_product = [
            [
                'product' => $product1->id,
                'qty' => 2,
                'rabais' => '25',
                'price' => null,
                'gratuit' => null,
                'prix' => $product1->price_cents,
                'computed' => $money->format($price1),
            ],
            [
                'product' => $product2->id,
                'qty' => 2,
                'rabais' => null,
                'price' => null,
                'gratuit' => 'oui',
                'prix' => $product2->price_cents,
                'computed' => $money->format(0),
            ]
        ];

        $total = $product1->price_cents + $product2->price_cents + 10;

        $user_mock->shouldReceive('find')->once()->andReturn($user);
        $product_mock->shouldReceive('find')->once()->andReturn($product1);
        $product_mock->shouldReceive('find')->once()->andReturn($product2);
        $maker_mock->shouldReceive('total')->twice()->andReturn($total);;

        $response = $this->call('POST', '/admin/order/verification', $data);

        $content = $response->getOriginalContent();
        $result = $content['data'];

        $this->assertEquals($result, $data);

        // Correction
        $product_mock->shouldReceive('find')->times(1)->andReturn($product1);
        $product_mock->shouldReceive('find')->times(1)->andReturn($product2);

        // Go back to creation
        $response = $this->post('/admin/order/correction', ['data' => json_encode($data)]);

        $response->isRedirect('admin/order/create');

        $products = $this->app['session.store']->get('old_products');
        $shipping_id = $this->app['session.store']->get('shipping_id');
        $user_id = $this->app['session.store']->get('user_id');

        $response->assertSessionHas('old_products');

        $this->assertEquals($data_product, $products);
        $this->assertEquals($user->id, $user_id);
        $this->assertEquals(1, $shipping_id);
    }
}
