<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class OrderGenerateTest extends TestCase
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

    public function testGetFilename()
    {
        $make  = new \tests\factories\ObjectFactory();
        $user  = $make->user();
        $order = $make->order(1, $user->id);
        $order = $order->first();

        $generate = new \App\Droit\Generate\Entities\OrderGenerate($order);

        $path = public_path('files/shop/factures/facture_'.$order->order_no.'.pdf');

        $this->assertEquals($generate->getFilename(), $path);
    }

    public function testGetRappelFilename()
    {
        $make  = new \tests\factories\ObjectFactory();
        $user  = $make->user();
        $order = $make->order(1, $user->id);
        $order = $order->first();

        $rappel = factory(\App\Droit\Shop\Rappel\Entities\Rappel::class)->create(['order_id' => $order->id]);

        $generate = new \App\Droit\Generate\Entities\OrderGenerate($order);

        $path = public_path('files/shop/rappels/rappel_'.$rappel->id.'_'.$order->order_no.'.pdf');

        $this->assertEquals($generate->getFilename($rappel), $path);
    }

    public function testGetAdresseUser()
    {
        $make  = new \tests\factories\ObjectFactory();
        $user  = $make->user();
        $order = $make->order(1, $user->id);
        $order = $order->first();

        $adresse = $user->adresse_facturation;

        $generate = new \App\Droit\Generate\Entities\OrderGenerate($order);

        $this->assertEquals($generate->getAdresse()->name, $adresse->name);
    }

    public function testGetAdresse()
    {
        $make    = new \tests\factories\ObjectFactory();
        $adresse = $make->adresse();

        $order = $make->makeAdresseOrder($adresse->id);

        $generate = new \App\Droit\Generate\Entities\OrderGenerate($order);

        $this->assertEquals($generate->getAdresse()->name, $adresse->name);
    }

    public function testGetNoAdresse()
    {
        $make  = new \tests\factories\ObjectFactory();
        $user  = factory(\App\Droit\User\Entities\User::class)->create();

        $order = $make->order(1, 1);
        $order = $order->first();

        $generate = new \App\Droit\Generate\Entities\OrderGenerate($order);
        $adresse  = $generate->getAdresse();

        $this->assertNull($adresse);
        //$this->expectException(\App\Exceptions\AdresseNotExistPrepareException::class);
    }
}
