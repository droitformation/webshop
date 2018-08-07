<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureCartTest extends TestCase
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
        // Login
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

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

        $this->assertEquals(2, $inCart->first()->qty);

        // Remove Product from basket
        $this->call('POST', 'pubdroit/cart/removeProduct', ['rowId' => $inCart->first()->rowId]);

        $this->assertTrue(\Cart::instance('shop')->content()->isEmpty());
    }

    public function testApplyCoupon()
    {
        // Login
        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $this->actingAs($person);

        // Make a product
        $make    = new \tests\factories\ObjectFactory();
        $product = $make->product();

        // Change qty
        $this->call('POST', 'pubdroit/cart/addProduct', ['product_id' => $product->id]);

        // Test if the product is in the cart
        $id = $product->id;

        $inCart = \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == (int)$id;
        });

        $this->assertTrue(!$inCart->isEmpty());

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create(['value' => 50, 'title' => 'TEST124']);

        // apply cooupon
        $this->call('POST', 'pubdroit/cart/applyCoupon', ['coupon' => $coupon->title]);

        $response = $this->get('pubdroit/checkout/resume');
        $response->assertSee('TEST124');

    }

    public function testProductHasNoWeightShippingIsNull()
    {
        $cartworker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');

        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $this->actingAs($person);

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'weight' => null,
            'price'  => 1000,
        ]);

        $this->call('POST', 'pubdroit/cart/addProduct', ['product_id' => $product->id]);

        $this->assertEquals('10.00',$cartworker->totalCartWithShipping());
        $this->assertEquals('0.00',$cartworker->totalShipping());
    }

    public function testProductHasPositivWeightShippingIsNotNull()
    {
        $cartworker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');

        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $this->actingAs($person);

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'weight' => 200,
            'price'  => 1000,
        ]);

        $this->call('POST', 'pubdroit/cart/addProduct', ['product_id' => $product->id]);

        $this->assertEquals('20.00',$cartworker->totalCartWithShipping());
        $this->assertEquals('10.00',$cartworker->totalShipping());
    }

    public function testProductHasCouponGlobal()
    {
        $cartworker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');

        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $this->actingAs($person);

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'weight' => 200,
            'price'  => 2000,
        ]);

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'     => '5',
            'type'      => 'price',
            'global'    => 1,
            'title'     => 'RABAIS',
            'expire_at' => \Carbon\Carbon::now()->addDay()
        ]);

        $coupon->products()->attach($product->id);

        $this->call('POST', 'pubdroit/cart/addProduct', ['product_id' => $product->id]);

        $this->assertEquals('25.00',$cartworker->totalCartWithShipping());
        $this->assertEquals('10.00',$cartworker->totalShipping());
    }
}
