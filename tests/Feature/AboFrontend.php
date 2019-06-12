<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\ResetTbl;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboFrontend extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $person;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->person = $user;
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testShopForAnAboButNoAdresses()
    {
        \Mail::fake();
        \Queue::fake();
        \Event::fake();

        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

        // Add abo in cart
        $reponse = $this->post('pubdroit/cart/addAbo', ['abo_id' => $abo->id]);
        $errors = $this->app['session.store']->all();

        $id = $abo->id;

        $inCart = \Cart::instance('abonnement')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });

        // Middleware prevents cart update => no adresse
        $this->assertTrue($inCart->isEmpty());
        $this->assertEquals(302, $reponse->getStatusCode());
        $this->assertTrue(isset($errors['AdresseMissing']));
    }

    public function testShopForAnAbo()
    {
        \Mail::fake();
        \Queue::fake();
        \Event::fake();

        $make     = new \tests\factories\ObjectFactory();
        $abo      = $make->makeAbo();
        $colloque = $make->colloque();

        // Add abo in cart
        $reponse = $this->post('pubdroit/cart/addAbo', ['abo_id' => $abo->id]);

        $id = $abo->id;

        $inCart = \Cart::instance('abonnement')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });

        $this->assertTrue(!$inCart->isEmpty());

        // Send order
        $response = $this->get('pubdroit/checkout/send', []);

        \Event::assertDispatched(\App\Events\NewAboRequest::class, function ($e) use ($id) {
            return $e->abos->contains('abo_id',$id);
        });

        $this->assertDatabaseHas('abo_users', [
            'user_id' => $this->person->id,
            'abo_id'  => $abo->id
        ]);

    }

    public function testAboOrderWithReference()
    {
        session()->put('reference_no', 'Ref_2019_1_DesignPond');
        session()->put('transaction_no', '2109_1_10_1982');

        \Mail::fake();
        \Queue::fake();
        \Event::fake();

        $make     = new \tests\factories\ObjectFactory();
        $abo      = $make->makeAbo();

        // Add abo in cart
        $reponse = $this->post('pubdroit/cart/addAbo', ['abo_id' => $abo->id]);

        // Send order
        $response = $this->get('pubdroit/checkout/send', []);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no' => 'Ref_2019_1_DesignPond',
            'transaction_no' => '2109_1_10_1982'
        ]);

        $this->assertDatabaseHas('abo_users', [
            'abo_id' => $abo->id,
            'user_id' => $this->person->id,
        ]);

    }
}
