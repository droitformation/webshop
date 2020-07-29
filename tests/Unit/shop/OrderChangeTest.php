<?php

namespace Tests\Unit\shop;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderChangeTest extends TestCase
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
        \DB::rollBack();
        parent::tearDown();
    }

    public function testPrepareOrderUpdateDataShippingSimple()
    {
        $order = $this->makeOrder();

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create();

        $request = [
            'id'          => $order->id,
            'created_at'  => '2018-01-01',
            'user_id'     => $order->user_id,
            'comment'     => ['warning' => 'Un commentaire'],
            'coupon_id'   => $coupon->id,
            'shipping_id' => $order->shipping_id,
            'paquet'      => 2
        ];

        $updater = new \App\Droit\Shop\Order\Worker\OrderUpdate($request,$order);
        $updated = $updater->updateOrder();

        $this->assertEquals('2018-01-01',$updated->created_at->format('Y-m-d'));
        $this->assertEquals(['warning' => 'Un commentaire'], unserialize($updated->comment));
        $this->assertEquals(2, $updated->paquet);
        $this->assertEquals($order->shipping_id, $updated->shipping_id);

    }

    public function testPrepareOrderUpdateDataShippingCalculated()
    {
        $order = $this->makeOrder();

        $request = [
            'id'      => $order->id,
            'user_id' => $order->user_id,
        ];

        $updater = new \App\Droit\Shop\Order\Worker\OrderUpdate($request,$order);
        $updated = $updater->updateOrder();

        $this->assertNull($updated->paquet);
        $this->assertNull($updated->shipping_id);
        $this->assertEquals(1,$updated->paquets->first()->qty);
        $this->assertEquals(3,$updated->paquets->first()->shipping_id);

    }

    public function makeOrder()
    {
        $make = new \tests\factories\ObjectFactory();

        $orders = $make->order(1);
        $order  = $orders->first();
        $order->products()->detach();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 5000, 'price'  => 3000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 5000, 'price'  => 3000]);

        $order->products()->attach([$product1->id,$product2->id]);
        $order->comment = serialize(['warning' => 'Testing']);
        $order->save();

        return $order->fresh();
    }
}
