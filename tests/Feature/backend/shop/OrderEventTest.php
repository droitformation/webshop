<?php

namespace Tests\Feature\backend\shop;

use Illuminate\Queue\Listener;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderEventTest extends TestCase
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

    public function testEventOrderUpdateWithProductNotifyUrl()
    {
        \Event::fake();

        $make = new \tests\factories\ObjectFactory();

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'notify_url' => 'http://publications-droit.ch'
        ]);

        $orders = $make->order(1);
        $order  = $orders->first();
        $order->products()->sync($product->id);
        $order = $order->load('products');

        $input = [
            'pk' => $order->id,
            'value' => '2016-05-12',
            'name' => 'payed_at'
        ];

        $this->json('POST', '/admin/order/edit', $input)->assertJsonFragment(['OK' => 200, 'etat' => 'Payé', 'color' => 'success']);

        \Event::assertDispatched(\App\Events\OrderUpdated::class, function ($e) use ($order) {
            return $e->order->id === $order->id;
        });
    }

    public function testNoEventOrderUpdate()
    {
        \Event::fake();

        $make   = new \tests\factories\ObjectFactory();
        $orders = $make->order(1);
        $order  = $orders->first();

        $input = [
            'pk'    => $order->id,
            'value' => '2018-01-01',
            'name'  => 'send_at'
        ];

        $this->json('POST', '/admin/order/edit', $input)->assertJsonFragment(["OK" => 200,"color"=> "info"]);

        \Event::assertNotDispatched(\App\Events\OrderUpdated::class);
    }

}
