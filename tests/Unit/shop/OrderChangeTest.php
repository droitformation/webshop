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

        $request = [
            'id'         => $order->id,
            'created_at' => '2018-01-01',
            'user_id'    => 710,
            'comment'    => 'Un commentaire'
        ];

        $updater = new \App\Droit\Shop\Order\Worker\OrderUpdate($request,$order);

        $updater->prepareData();

        $this->assertEquals($request,$updater->data);

    }

}
