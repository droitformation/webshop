<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderBoxTest extends TestCase
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
        \DB::rollBack();
        parent::tearDown();
    }

    public function testPrepareOrderDataFromAdmin()
    {
        // Dependencies
        $product  = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 3000, 'price'  => 1000]);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $order = [
            'user_id' => $user->id,
            'order'   => [
                'products' => [0 => $product->id],
                'qty'      => [0 => 1],
            ],
            'admin' => 1
        ];

        $orderbox = new \App\Droit\Shop\Order\Entities\OrderBox($order);

        $paquets = $orderbox->calculate(3000)->getShippingList();

        $this->assertEquals(1,count($paquets));

    }

    public function testPrepareOrderDataFromAdmin2()
    {
        // Dependencies
        $product1  = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000]);
        $product2  = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000]);
        $product3  = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 1000, 'price'  => 1000]);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $order = [
            'user_id' => $user->id,
            'order'   => [
                'products' => [0 => $product1->id, 1 => $product2->id, $product3->id],
                'qty'      => [0 => 20, 1 => 10, 2 => 15],
            ],
            'admin' => 1
        ];

        $expect = collect([
            '30 Kg | 26.00' => 1,
            '20 Kg | 19.00' => 1,
        ]);

        $list = collect([
            5 => ['shipping_id' => 5, 'qty' => 1],
            4 => ['shipping_id' => 4, 'qty' => 1],
        ]);

        $orderbox = new \App\Droit\Shop\Order\Entities\OrderBox($order);
        $orderbox->calculate(45000); // (1000 * 20) + (1000 * 10) + (1000 * 15)

        $paquets  = $orderbox->getShippingList();
        $boxes    = $orderbox->getListBoxes();

        $this->assertEquals(2,count($paquets));
        $this->assertEquals($list,$paquets);
        $this->assertEquals('45.00', $orderbox->getTotalShippingPrice());
        $this->assertEquals($expect,$boxes);

    }

    public function testPrepareOrderDataFromAdminFree()
    {
        // Dependencies
        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 2000, 'price'  => 1000]);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $order = [
            'user_id' => $user->id,
            'order'   => [
                'products' => [0 => $product->id],
                'qty'      => [0 => 20],
            ],
            'admin' => 1
        ];

        $orderbox = new \App\Droit\Shop\Order\Entities\OrderBox($order);

        $shipping = $orderbox->getFreeShipping();

        $this->assertEquals(0,$shipping->price_cents);
    }
}
