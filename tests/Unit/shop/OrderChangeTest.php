<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderChangeTest extends TestCase
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

    public function testPrepareOrderUpdateDataFromAdmin()
    {
        $make = new \tests\factories\ObjectFactory();

        $orders = $make->order(1);
        $order  = $orders->first();
        $order->products()->detach();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 5000, 'price'  => 3000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['weight' => 5000, 'price'  => 3000]);

        $order->products()->attach([$product1->id,$product2->id]);

        $order = $order->fresh();

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value' => 10, // -10
            'title' => 'PHP',
            'type'  => 'global',
            'expire_at' => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);

        $request = [
            'id'          => $order->id,
            'created_at'  => '2018-01-01',
            'user_id'     => 710,
            'comment'     => 'Un commentaire',
            'coupon_id'   => $coupon->id,
            'shipping_id' => $order->shipping_id,
            'paquet' => 2
        ];

        $updater = new \App\Droit\Shop\Order\Worker\OrderUpdate($request,$order);

        $updater->prepareData();
        echo '<pre>';
        print_r($updater->data);
        echo '</pre>';exit();
        $this->assertEquals($request,$updater->data);

    }

}
