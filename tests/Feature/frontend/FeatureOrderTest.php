<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeaturOrderTest extends TestCase
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

    public function testOrderInShop()
    {


        // Make a product
        $make    = new \tests\factories\ObjectFactory();
        $product = $make->product();

        // Change qty
        $this->call('POST', 'pubdroit/cart/addProduct', ['product_id' => $product->id]);

        // Test if the product is in the cart
        $id =  $product->id;

        $inCart = \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == (int)$id;
        });

        $this->assertTrue(!$inCart->isEmpty());

        // Change qty
        $this->call('POST', 'pubdroit/cart/quantityProduct', ['rowId' => $inCart->first()->rowId, 'qty' => 2]);

        $inCart = \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == (int)$id;
        });


        $this->assertTrue(\Cart::instance('shop')->content()->isEmpty());
    }


}
